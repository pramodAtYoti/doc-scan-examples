package main

import (
	"crypto/tls"
	"encoding/json"
	"fmt"
	"html/template"
	"io/ioutil"
	"net/http"
	"os"

	"github.com/getyoti/yoti-go-sdk/v2/requests"
	_ "github.com/joho/godotenv/autoload"
)

type jsonobj map[string]interface{}

const (
	selfSignedKeyName  = "SelfSignedKey.pem"
	selfSignedCertName = "SelfSignedCert.pem"
	portNumber         = 8080
)

func home(w http.ResponseWriter, r *http.Request) {
	sdkID := os.Getenv("YOTI_CLIENT_SDK_ID")
	baseURL := os.Getenv("YOTI_BASE_URL")
	keyFile := os.Getenv("YOTI_KEY_FILE_PATH")

	key, err := ioutil.ReadFile(keyFile)
	if err != nil {
		panic("Could not read key file: " + err.Error())
	}

	// Create session
	sessionRequest, err := requests.SignedRequest{
		HTTPMethod: http.MethodPost,
		BaseURL:    baseURL,
		Endpoint:   "/sessions",
		Params: map[string]string{
			"sdkId": sdkID,
		},
		Headers: map[string][]string{
			"Content-Type": {"application/json"},
			"Accept":       {"application/json"},
		},
		Body: func(data []byte, _ error) []byte {
			return data
		}(json.Marshal(jsonobj{
			"notifications": jsonobj{
				"endpoint": "https://127.0.0.1/updates",
				"topics":   []string{"session_completion"},
			},
			"requested_checks": []jsonobj{
				jsonobj{
					"type":   "ID_DOCUMENT_AUTHENTICITY",
					"config": make(jsonobj),
				},
			},
			"requested_tasks": []jsonobj{
				jsonobj{
					"type": "ID_DOCUMENT_TEXT_DATA_EXTRACTION",
					"config": jsonobj{
						"manual_check": "FALLBACK",
					},
				},
			},
			"sdk_config": jsonobj{
				"allowed_capture_methods": "CAMERA_AND_UPLOAD",
				"primary_colour":          "#2d9fff",
				"preset_issuing_country":  "USA",
				"success_url":             fmt.Sprintf("https://localhost:%d/success", portNumber),
				"error_url":               fmt.Sprintf("https://localhost:%d/error", portNumber),
			},
		})),
	}.WithPemFile(key).Request()
	if err != nil {
		panic("Problem creating docscan session: " + err.Error())
	}

	fmt.Println("Requesting session")
	var session map[string]interface{}
	sessionResponse, err := http.DefaultClient.Do(sessionRequest)
	if err != nil {
		panic("Problem creating docscan session: " + err.Error())
	}
	if sessionResponse.StatusCode != 201 {
		message, _ := ioutil.ReadAll(sessionResponse.Body)
		panic(fmt.Sprintf("Problem creating docscan session: %d: %s", sessionResponse.StatusCode, message))
	}

	raw, _ := ioutil.ReadAll(sessionResponse.Body)
	err = json.Unmarshal(raw, &session)
	if err != nil {
		panic("Problem parsing session data JSON")
	}

	// Render Template
	templateVars := map[string]interface{}{
		"baseURL":      baseURL,
		"sessionID":    session["session_id"],
		"sessionToken": session["client_session_token"],
	}
	page, err := template.ParseFiles("docscan.html")
	if err != nil {
		panic("Error parsing template: " + err.Error())
	}
	err = page.Execute(w, templateVars)
	if err != nil {
		panic("Error applying the parsed template: " + err.Error())
	}
}

func main() {
	_, insecureTLS := os.LookupEnv("TLS_INSECURE")
	if insecureTLS {
		fmt.Println("WARNING: TLS Certificate checking disabled")
		http.DefaultTransport.(*http.Transport).TLSClientConfig = &tls.Config{InsecureSkipVerify: true}
	}

	certificatePresent := certificatePresenceCheck(selfSignedCertName, selfSignedKeyName)
	if !certificatePresent {
		fmt.Println("Generating self-signed certificates")
		err := generateSelfSignedCertificate(
			selfSignedCertName,
			selfSignedKeyName,
			fmt.Sprintf("127.0.0.1:%d", portNumber),
		)
		if err != nil {
			panic("Error creating certs: " + err.Error())
		}
	}

	http.HandleFunc("/", home)

	fmt.Printf("Starting server on port %d\n", portNumber)
	err := http.ListenAndServeTLS(fmt.Sprintf(":%d", portNumber), selfSignedCertName, selfSignedKeyName, nil)
	if err != nil {
		panic("Error when calling `ListenAndServeTLS`: " + err.Error())
	}
}
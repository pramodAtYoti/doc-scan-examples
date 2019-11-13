# GO Doc-Scan Example

- Clone this project
- Create an application on https://www.yoti.com/hub and download your private keys and SDK ID
- Request the API base URL from sdksupport@yoti.com
- Copy your PEM file into `/keys` and rename to `key.pem`
- Create a .env file using the example `.env.example` file
- Fill in the missing environment variables using your SDK ID; route to your private key .pem file; the API base URL and your host URL
- Run the project with `go run main.go`
- Go to your host URL to see the project

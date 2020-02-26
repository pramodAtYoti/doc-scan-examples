using System;
using System.IO;
using System.Net.Http;
using System.Text;
using Microsoft.AspNetCore.Mvc.RazorPages;
using Yoti.Auth;
using Yoti.Auth.Web;

namespace Yoti.DocScanDemo.Pages
{
    public class IndexModel : PageModel
    {
        private HttpClient _httpClient = new HttpClient();

        public HttpResponseMessage HttpResponseMessage { get; set; }
        public string PageContent { get; set; }

        public void OnGet()
        {
            CreateSession();
        }

        private void CreateSession()
        {
            StreamReader privateKeyStream = System.IO.File.OpenText(Environment.GetEnvironmentVariable("YOTI_KEY_FILE_PATH"));
            var key = CryptoEngine.LoadRsaKey(privateKeyStream);

            string clientSdkId = Environment.GetEnvironmentVariable("YOTI_CLIENT_SDK_ID");

            string host = Environment.GetEnvironmentVariable("BASE_URL");

            if (string.IsNullOrEmpty(host))
            {
                throw new ArgumentNullException("Ensure the BASE_URL environment variable is specified");
            }

            string requestJson;
            using (StreamReader r = System.IO.File.OpenText("Request.json"))
            {
                requestJson = r.ReadToEnd();
            }

            byte[] byteContent = Encoding.UTF8.GetBytes(requestJson);

            Uri docScanUri = new UriBuilder(scheme: "https", host: host, port: 443, pathValue: "/idverify/v1").Uri;

            Request docScanRequest = new Yoti.Auth.Web.RequestBuilder()
                .WithKeyPair(key)
                .WithHttpMethod(HttpMethod.Post)
                .WithBaseUri(docScanUri)
                .WithEndpoint("/sessions")
                .WithQueryParam("sdkId", clientSdkId)
                .WithContent(byteContent)
                .Build();

            HttpResponseMessage = docScanRequest.Execute(_httpClient).Result;

            PageContent = HttpResponseMessage.Content.ReadAsStringAsync().Result;
        }
    }
}
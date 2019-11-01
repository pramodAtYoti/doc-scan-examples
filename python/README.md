## Yoti Docs Scan Example Project (Python)

### Setup

* Modify the .env file with the corresponing credentials.
  * If you are using `Docker`, please place your .pem file in the root directory, and configure the .env file to use the root directory of /code (e.g. if your pem file is called my-key.pem, set `YOTI_KEY_FILE_PATH` to /code/my-key.pem)
  * If you are running the Django project manually, please use the full path to your PEM file.

* To run with `Docker`, run with `docker-compose up`.
* To run manually, run the following steps:

```bash
pip install -r requirements.txt
python manage.py migrate
python manage.py runsslserver
```

### Using the application

* Navigate to `https://localhost:8000/`.  Based on the users session ID, a Yoti Doc Scan session will be created
and stored in the users Django session.
* Follow the on-screen steps by scanning an ID document.
* If successful, the browser will redirect to `https://localhost:8000/success` where it will display a basic page with information
retrieved from the Yoti Doc Scan API.
* You can click the `View JSON` button to view the whole session response as raw JSON.
* ID checks are not instant, so you can use the `Refresh` button to keep checking the state of the check.
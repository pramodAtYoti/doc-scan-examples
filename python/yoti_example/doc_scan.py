from yoti_python_sdk.http import SignedRequest
from app_settings import (
    YOTI_BASE_URL,
    YOTI_CLIENT_SDK_ID,
    YOTI_KEY_FILE_PATH
)

import json

from yoti_python_sdk.http import RequestHandler
import requests


class ImageRequestHandler(RequestHandler):

    @staticmethod
    def execute(request):
        response = requests.request(
            url=request.url, data=request.data, headers=request.headers, method=request.method)
        return response.content


def generate_session(user_session_id):
    payload = {
        "client_session_token_ttl": 600,
        "resources_ttl": 604800,
        "user_tracking_id": user_session_id,
        "requested_checks": [
            {
                "type": "ID_DOCUMENT_AUTHENTICITY",
                "config": {}
            },
        ],
        "requested_tasks": [
            {
                "type": "ID_DOCUMENT_TEXT_DATA_EXTRACTION",
                "config": {
                    "manual_check": "FALLBACK"
                }
            }
        ],
        "sdk_config": {
            "allowed_capture_methods": "CAMERA_AND_UPLOAD",
            "primary_colour": "#2d9fff",
            "preset_issuing_country": "USA",
            "success_url": "https://localhost:8000/success",
            "error_url": "https://localhost:8000/error"
        }
    }

    payload_string = json.dumps(payload).encode()

    signed_request = (
        SignedRequest
        .builder()
        .with_pem_file(YOTI_KEY_FILE_PATH)
        .with_base_url(YOTI_BASE_URL)
        .with_endpoint("/sessions")
        .with_http_method("POST")
        .with_param("sdkId", YOTI_CLIENT_SDK_ID)
        .with_payload(payload_string)
        .build()
    )

    response = signed_request.execute()
    response_payload = json.loads(response.text)

    client_session_token = response_payload["client_session_token"]
    session_id = response_payload["session_id"]

    return session_id, client_session_token


def get_data_for_session(session_id):
    signed_request = (
        SignedRequest
        .builder()
        .with_pem_file(YOTI_KEY_FILE_PATH)
        .with_base_url(YOTI_BASE_URL)
        .with_endpoint("/sessions/" + session_id)
        .with_http_method("GET")
        .with_param("sdkId", YOTI_CLIENT_SDK_ID)
        .build()
    )

    response = signed_request.execute()
    response_payload = json.loads(response.text)
    return response_payload


def get_media(session_id, media_id):
    signed_request = (
        SignedRequest
        .builder()
        .with_pem_file(YOTI_KEY_FILE_PATH)
        .with_base_url(YOTI_BASE_URL)
        .with_endpoint("/sessions/" + session_id + "/media/" + media_id + "/content")
        .with_http_method("GET")
        .with_request_handler(ImageRequestHandler)
        .with_param("sdkId", YOTI_CLIENT_SDK_ID)
        .build()
    )

    response = signed_request.execute()
    return response

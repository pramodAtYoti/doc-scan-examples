from django.views.generic import TemplateView
from django.shortcuts import render, redirect, HttpResponse
from django.http import JsonResponse

from yoti_example.doc_scan import generate_session, get_data_for_session, get_media


class IndexView(TemplateView):
    template_name = "index.html"

    def get(self, request, *args, **kwargs):
        if not request.session.session_key:
            request.session.save()

        if not hasattr(request.session, "yoti_client_token") or not hasattr(request.session, "yoti_session_id"):
            session_id, client_session_token = generate_session(
                request.session.session_key)

            request.session["yoti_session_id"] = session_id
            request.session["yoti_client_token"] = client_session_token

        return render(
            request,
            self.template_name,
            context={
                "session_id": request.session["yoti_session_id"],
                "client_token": request.session["yoti_client_token"]
            }
        )


class SuccessView(TemplateView):
    template_name = "success.html"

    def get(self, request, *args, **kwargs):
        if not request.session.session_key:
            print("Should not have a session key here: " +
                  request.session.session_key)
            return redirect("index")

        if (
            not request.session["yoti_client_token"] or
            not request.session["yoti_session_id"]
        ):
            return redirect("index")

        session_id = request.session["yoti_session_id"]
        session_data = get_data_for_session(session_id)

        return render(request, self.template_name, session_data)


class SuccessJsonView(TemplateView):
    template_name = "success.html"

    def get(self, request, *args, **kwargs):
        if not request.session.session_key:
            print("Should not have a session key here: " +
                  request.session.session_key)
            return redirect("index")

        if (
            not request.session["yoti_client_token"] or
            not request.session["yoti_session_id"]
        ):
            return redirect("index")

        session_id = request.session["yoti_session_id"]
        session_data = get_data_for_session(session_id)

        return JsonResponse(session_data, json_dumps_params={"indent": 2})


class ErrorView(TemplateView):
    template_name = "error.html"

    def get(self, request, *args, **kwargs):
        return render(request, self.template_name)


def media_view(request, media_id):
    media_id = str(media_id)
    response_text = get_media(request.session["yoti_session_id"], media_id)
    return HttpResponse(response_text)


def media_view_image(request, media_id):
    media_id = str(media_id)
    response_text = get_media(request.session["yoti_session_id"], media_id)
    response = HttpResponse(response_text, content_type="image/jpeg")
    return response

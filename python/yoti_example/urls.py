"""yoti_example URL Configuration

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/1.10/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  url(r'^$', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  url(r'^$', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.conf.urls import url, include
    2. Add a URL to urlpatterns:  url(r'^blog/', include('blog.urls'))
"""
from django.conf.urls import url
from django.urls import path

from .views import IndexView, ErrorView, SuccessView, SuccessJsonView, media_view, media_view_image

urlpatterns = [
    url(r"^$", IndexView.as_view(), name="index"),
    url(r"^error/$", ErrorView.as_view(), name="error"),
    url(r"^success/$", SuccessView.as_view(), name="success"),
    url(r"^success/json/$", SuccessJsonView.as_view(), name="success-json"),
    path("media/<slug:media_id>/", media_view, name="media"),
    path("media/image/<slug:media_id>/", media_view_image, name="media-image")
]

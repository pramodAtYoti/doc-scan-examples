const { RequestBuilder } = require("yoti");

/* Function to delete media data
   The data extraction tasks must be completed to delete data from document.
   Need an active session_id and the media_id from that session
   Only deletes specified media not all media or session. */
const deleteMedia = async (sessionId, mediaId) => {
  const request = new RequestBuilder()
    .withBaseUrl(config.API_BASE_URL)
    .withPemFilePath(config.PEM_FILE_PATH)
    .withEndpoint(`/sessions/${sessionId}/media/${mediaId}/content`)
    .withMethod("DELETE")
    .withQueryParam("sdkId", config.CLIENT_SDK_ID)
    .build();

  try {
    const deleteRequest = await request.execute();
    console.log(deleteRequest);
  } catch (e) {
    console.log(e);
  }
};

/* Function to retieve media data
   The data extraction tasks must be completed to retrieve data from document.
   Need an active session_id and the media_id from that session */
const retrieveMedia = async (sessionId, mediaId) => {
  const request = new RequestBuilder()
    .withBaseUrl(config.API_BASE_URL)
    .withPemFilePath(config.PEM_FILE_PATH)
    .withEndpoint(`/sessions/${sessionId}/media/${mediaId}/content`)
    .withMethod("GET")
    .withQueryParam("sdkId", config.CLIENT_SDK_ID)
    .build();

  try {
    const getResult = await request.execute();
    const { parsedResponse } = getResult;
    console.log(parsedResponse);
  } catch (e) {
    console.log(e);
  }
};

module.exports = {
  deleteMedia,
  retrieveMedia
};

const { retrieveMedia, deleteMedia } = require("./mediaData");
const { RequestBuilder, Payload } = require("yoti");

/* Function to create a session using a user defined session object. 
 Returns a URL with session_id and client_session_token */
const createSession = async session => {
  const request = new RequestBuilder()
    .withBaseUrl(config.API_BASE_URL)
    .withPemFilePath(config.PEM_FILE_PATH)
    .withEndpoint("/sessions")
    .withPayload(new Payload(session))
    .withMethod("POST")
    .withQueryParam("sdkId", config.CLIENT_SDK_ID)
    .build();

  try {
    const postRequest = await request.execute();
    const {
      parsedResponse: { session_id, client_session_token }
    } = postRequest;
    //url for frontend with session ID and token
    let docsUrl =
      config.API_BASE_URL +
      "/web/index.html" +
      "?sessionID=" +
      session_id +
      "&sessionToken=" +
      client_session_token;
    return docsUrl;
  } catch (e) {
    console.log(e);
  }
};

/* Function to delete session before it expires and is deleted automatically
   The session must be completed before it can be deleted. */
const deleteSession = async sessionId => {
  const request = new RequestBuilder()
    .withBaseUrl(config.API_BASE_URL)
    .withPemFilePath(config.PEM_FILE_PATH)
    .withEndpoint(`/sessions/${sessionId}`)
    .withMethod("DELETE")
    .withQueryParam("sdkId", config.CLIENT_SDK_ID)
    .build();

  try {
    const deleteRequest = await request.execute();
    console.log(deleteRequest.getStatusCode());
  } catch (e) {
    console.log(e);
  }
};

/* Function to retreive the session from Yoti. 
   This can be done at any time after the session has been created. */
const retrieveSession = async sessionId => {
  const request = new RequestBuilder()
    .withBaseUrl(config.API_BASE_URL)
    .withPemString(config.PEM_KEY)
    .withEndpoint(`/sessions/${sessionId}`)
    .withMethod("GET")
    .withQueryParam("sdkId", config.CLIENT_SDK_ID)
    .build();

  try {
    const getResult = await request.execute();
    const { parsedResponse } = getResult;
    // Log retrieved session object
    console.log(JSON.stringify(parsedResponse, null, 2));

    /*  Retrieve media from session
      Document image */
    // await retrieveMedia(
    //   sessionId,
    //   parsedResponse.resources.id_documents[0].pages[0].media.id
    // );
    /*  Document data extraction */
    // await retrieveMedia(
    //   sessionId,
    //   parsedResponse.resources.id_documents[0].tasks[0].generated_media[0].id
    // );

    /*  Delete media from session
      Document image */
    // await deleteMedia(
    //   sessionId,
    //   parsedResponse.resources.id_documents[0].pages[0].media.id
    // );
    /*  Document data extraction */
    // await deleteMedia(
    //   sessionId,
    //   parsedResponse.resources.id_documents[0].tasks[0].generated_media[0].id
    // );
  } catch (e) {
    console.log(e);
  }
};

module.exports = {
  createSession,
  deleteSession,
  retrieveSession
};

require("dotenv").config();
const { createSession } = require("../js/sessionData");
const express = require("express");
const router = express.Router();

//create session
router.get("/", async (req, res) => {
  //configure session object
  let session = {
    client_session_token_ttl: 600,
    resources_ttl: 604800,
    user_tracking_id: "",
    notifications: {
      endpoint: `${config.HOST}/update`,
      topics: [
        "resource_update",
        "task_completion",
        "check_completion",
        "session_completion"
      ],
      auth_token: "XXX"
    },
    requested_checks: [
      {
        type: "ID_DOCUMENT_AUTHENTICITY",
        config: {}
      }
    ],
    requested_tasks: [
      {
        type: "ID_DOCUMENT_TEXT_DATA_EXTRACTION",
        config: {
          manual_check: "NEVER"
        }
      }
    ],
    sdk_config: {
      allowed_capture_methods: "CAMERA_AND_UPLOAD",
      primary_colour: "#2d9fff",
      preset_issuing_country: "GBR",
      success_url: `${config.HOST}/success`,
      error_url: `${config.HOST}/error`
    }
  };
  //Post request and receive sessionID and sessionToken from Yoti
  docScanUrl = await createSession(session);
  res.render("index", {
    docScanUrl
  });
});

module.exports = router;

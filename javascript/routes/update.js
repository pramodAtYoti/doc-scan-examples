require("dotenv").config();
const { deleteSession, retrieveSession } = require("../js/sessionData");
const express = require("express");
const router = express.Router();

//notifications - defined in session object
router.post("/", async (req, res) => {
  //log notifications
  console.log(req.body);

  //retrieve session on completion
  if (req.body.topic === "session_completion") {
    // retrieve session
    await retrieveSession(req.body.session_id);

    // delete session
    // await deleteSession(req.body.session_id);
  }
});

module.exports = router;

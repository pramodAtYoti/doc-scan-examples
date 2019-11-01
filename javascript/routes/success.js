const express = require("express");
const router = express.Router();

//success_url  - defined in session object - SDK config
router.get("/", (req, res) => {
  res.render("success");
});

module.exports = router;

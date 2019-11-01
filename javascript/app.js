require("dotenv").config();
const fs = require("fs");

global.config = {
  CLIENT_SDK_ID: process.env.YOTI_CLIENT_SDK_ID, // Your Yoti Client SDK ID
  PEM_KEY: fs.readFileSync(process.env.YOTI_KEY_FILE_PATH), // The content of your Yoti .pem key
  PEM_FILE_PATH: process.env.YOTI_KEY_FILE_PATH,
  API_BASE_URL: process.env.BASE_URL, //The base url of API. Please contact Yoti for this on sdksupport@yoti.com
  API_PATH: "/sessions", //URI path
  HOST: process.env.CLIENT_HOST //Your domain name -  must be https://
};

const createError = require("http-errors");
const express = require("express");
const path = require("path");
const cookieParser = require("cookie-parser");
const app = express();
// view engine setup
app.set("views", path.join(__dirname, "views"));
app.set("view engine", "hbs");
app.use(express.json());
app.use(
  express.urlencoded({
    extended: false
  })
);
app.use(cookieParser());
app.use(express.static(path.join(__dirname, "public")));

const indexRouter = require("./routes/index");
const updateRouter = require("./routes/update");
const successRouter = require("./routes/success");

app.use("/", indexRouter);
app.use("/update", updateRouter);
app.use("/success", successRouter);

// catch 404 and forward to error handler
app.use(function(req, res, next) {
  next(createError(404));
});

// error handler
app.use(function(err, req, res, next) {
  // set locals, only providing error in development
  res.locals.message = err.message;
  res.locals.error = req.app.get("env") === "development" ? err : {};
  // render the error page
  res.status(err.status || 500);
  res.render("error");
});

module.exports = app;

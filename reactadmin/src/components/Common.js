const SUCCESS = "Success";
const FAILED = "Failed";
var API_ROOT_PATH = "";
if (process.env.NODE_ENV === "production") {
  API_ROOT_PATH = "http://www.deliciouspakwan.com";
} else {
  API_ROOT_PATH = "http://local.deliciouspakwan.com";
}
const FOOD_API_KEY = "dfU8dKV1ByM0zMKzRVpZtnP4GxvPQ7lgzPUCdFUj";

//COMMON API CALL
var fetchAPIData = function (
  apiPath,
  params = "",
  method = "POST",
  fupld = false,
  apiType = "Internal"
) {
  if (apiType === "Internal") {
    apiPath = API_ROOT_PATH + apiPath;
  }
  var apipara = {};

  return new Promise(function (resolve) {
    if (method === "POST" && fupld === false) {
      apipara = {
        method: method,
        redirect: "manual",
        mode: "cors",
        body: JSON.stringify(params),
        headers: {
          Referer: window.location.href,
          "Content-Type": "application/json",
        },
      };
    }
    if (method === "POST" && fupld === true) {
      apipara = {
        method: method,
        redirect: "manual",
        mode: "cors",
        body: JSON.stringify(params),
        headers: {
          Referer: window.location.href,
          "Content-Type": "multipart/form-data",
        },
      };
    }
    if (method === "GET") {
      apipara = {
        method: method,
        redirect: "manual",
        mode: "cors",
        headers: { Referer: window.location.href },
      };
    }
    fetch(apiPath, apipara)
      .then(function (response) {
        if (response.status === 200) {
          response.json().then(function (json) {
            resolve(json);
          });
        } else if (response.status === 400) {
          response.json().then(function (json) {
            window.onerror(JSON.stringify(json));
            resolve(json);
          });
        } else {
          window.onerror(JSON.stringify(response));
          resolve(response);
        }
      })
      .catch((err) => {
        console.warn(err);
      });
  });
};
var date = new Date();
var TodaysDate =
  (date.getMonth() > 8 ? date.getMonth() + 1 : "0" + (date.getMonth() + 1)) +
  "/" +
  (date.getDate() > 9 ? date.getDate() : "0" + date.getDate()) +
  "/" +
  date.getFullYear();

var YTStartingDate = "05/09/2020";
const isAuthenticated = () => {
  if (sessionStorage.getItem("login")) {
    return true;
  } else {
    return false;
  }
};
const clientId =
  "784293171882-krkjk6q95sg9fv7b08sh5ggf93lbp79n.apps.googleusercontent.com";

export {
  fetchAPIData,
  isAuthenticated,
  clientId,
  SUCCESS,
  FAILED,
  TodaysDate,
  YTStartingDate,
  API_ROOT_PATH,
  FOOD_API_KEY,
};

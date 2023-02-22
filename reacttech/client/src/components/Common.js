var API_ROOT_PATH = "";
if (process.env.NODE_ENV === "production") {
  API_ROOT_PATH = "http://techpyapi.deliciouspakwan.com";
} else {
  API_ROOT_PATH = "http://127.0.0.1:5000";
}
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
const getCookie = (cname) => {
  var name = cname + "=";
  var ca = document.cookie.split(";");
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) === " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) === 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
};
// const deleteCookies = () => {
//   document.cookie = "userToken=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
//   document.cookie = "userName=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
//   document.cookie = "userType=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
//   document.cookie = "expires=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
//   document.cookie = "userID=;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
//   document.cookie = `userLastLogged=;expires=Thu, 01 Jan 1970 00:00:01 GMT;`;
//   console.log(document.cookie === "");

// };
function deleteDailyReportCookies() {
  var cookies = document.cookie.split(";");
  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i];
    var eqPos = cookie.indexOf("=");
    var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
    document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;";
  }
  window.location.replace("/");
  console.log(document.cookie);
}

function buildCurrentDateFormat(date) {
  var monthNames = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];
  var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  var today = new Date(date);
  var dd = today.getDate();
  var mm = monthNames[today.getMonth()];
  var yyyy = today.getFullYear();
  var day = days[today.getDay()];
  today = `${day}, ${dd} ${mm} ${yyyy}`;
  return today;
}
const authenticateAdmin = () => {
  setTimeout(() => {
    if (
      sessionStorage.getItem("adminToken") &&
      sessionStorage.getItem("adminLogin") &&
      sessionStorage.getItem("adminID")
    ) {
      console.log(
        sessionStorage.getItem("adminToken") &&
          sessionStorage.getItem("adminLogin") &&
          sessionStorage.getItem("adminID")
      );
    } else {
      window.location.replace("/admin");
    }
  }, 10);
};
const getLangTableName = (techType) => {
  if (techType === "fe") {
    return "tech_site_fe_lang";
  } else if (techType === "be") {
    return "tech_site_be_lang";
  } else {
    return "tech_site_api_lang";
  }
};
const getLangName = (techType) => {
  if (techType === "fe") {
    return "Front End Languages";
  } else if (techType === "be") {
    return "Back End Languages";
  } else {
    return "API Languages";
  }
};
export {
  fetchAPIData,
  API_ROOT_PATH,
  getCookie,
  deleteDailyReportCookies,
  buildCurrentDateFormat,
  authenticateAdmin,
  getLangTableName,
  getLangName,
};

import { isMobile } from "react-device-detect";

var API_ROOT_PATH = "";
if (process.env.NODE_ENV === "production") {
  API_ROOT_PATH = "http://www.deliciouspakwan.com";
} else {
  API_ROOT_PATH = "http://local.deliciouspakwan.com";
}
var ALBUM_SONG_LOCATION_PATH = `${API_ROOT_PATH}/audio/albumsongs/`;
const ALBUM_CAROUSEL_TYPE = "ALBUM_CAROUSEL_TYPE";
const SHOW_ALBUM_SONGPLAYER = "SHOW_ALBUM_SONGPLAYER";
//COMMON API CALL
var fetchAPIData = function (
  apiPath,
  params = "",
  method = "POST",
  fupld = false
) {
  apiPath = API_ROOT_PATH + apiPath;
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

const getAlbumPhotoData = async (albumId, albumFolderTitle) => {
  if (localStorage.getItem("ALBUM_ID") === null) {
    localStorage.setItem("ALBUM_ID", albumId);
    localStorage.setItem("ALBUM_FOLDER_TITLE", albumFolderTitle);
  }
  const formData = {
    DISPLAY: "FE",
    ALBUM_ID: albumId,
    ALBUM_FOLDER_TITLE: albumFolderTitle,
  };
  const retData = {};
  await fetchAPIData("/api/albums/album_photos_list.php", formData, "POST")
    .then((json) => {
      if (json.status === "Success") {
        retData.albumPhotos = json.records[1];
        retData.albumDisplayTitle = json.records[0];
        retData.loading = false;
        window.scrollTo(0, 33);
      } else {
        window.location.replace("/");
      }
    })
    .catch((err) => {
      console.log(err);
    });
  return retData;
};

const getAlbumVideosData = async () => {
  var albumId = window.location.href.split("/")[5];
  var albumFolderTitle = window.location.href.split("/")[4];
  const formData = {
    DISPLAY: "FE",
    ALBUM_ID: albumId,
    ALBUM_FOLDER_TITLE: albumFolderTitle,
  };
  const retData = {};
  await fetchAPIData("/api/albums/album_videos_list.php", formData, "POST")
    .then((json) => {
      if (json.status === "Success") {
        retData.albumVideos = json.records[1];
      } else {
        window.location.replace("/");
      }
    })
    .catch((err) => {
      console.log(err);
    });
  return retData;
};

const getCarouselData = async () => {
  var albumId = window.location.href.split("/")[5];
  var albumFolderTitle = window.location.href.split("/")[4];
  var albumAccessInfo = window.location.href.split("/")[6];
  var adminKey = window.location.href.split("/")[7];
  var albumType = window.location.href.split("/")[8];

  if (localStorage.getItem("redirectFromParent") === true) {
    localStorage.setItem("redirectFromParent", false);
    return await getAlbumPhotoData(albumId, albumFolderTitle);
  } else {
    const privateCheckResult = await checkForPrivateAlbum(
      albumId,
      albumType,
      adminKey,
      albumAccessInfo
    );
    if (privateCheckResult === true) {
      return await getAlbumPhotoData(albumId, albumFolderTitle);
    } else {
      window.location.replace("/");
    }
  }
};

const checkForPrivateAlbum = async (
  albumId,
  albumType,
  adminKey,
  albumAccessInfo
) => {
  if (albumType === "PB") {
    return true;
  } else {
    const formData = {
      ALBUM_ID: albumId,
      ALBUM_ACCESS_KEY: albumAccessInfo,
      ADMIN_KEY: adminKey,
    };
    return await fetchAPIData(
      "/api/albums/checkfor_private_album.php",
      formData,
      "POST"
    )
      .then((json) => {
        if (json.records === true) {
          return true;
        } else {
          return false;
        }
      })
      .catch((err) => {
        console.log(err);
      });
  }
};

const updateVisitCounter = async () => {
  var albumId = window.location.href.split("/")[5];
  const formData = {
    ALBUM_ID: albumId,
  };
  const retData = {};
  await fetchAPIData(
    "/api/albums/update_album_visit_counter.php",
    formData,
    "POST"
  )
    .then((json) => {
      if (json.status === "Success") {
        retData.records = json.records;
      }
    })
    .catch((err) => {
      console.log(err);
    });
  return retData;
};

const getAlbumSongsDisplaySettings = async () => {
  const retData = {};
  const formData = {
    DISPLAY_NAME: SHOW_ALBUM_SONGPLAYER,
  };
  await fetchAPIData("/api/albums/get_react_comp_setting.php", formData, "POST")
    .then((json) => {
      if (json.status === "Success") {
        const mobileDevice = json.records.MOBILE;
        const desktopDevice = json.records.DESKTOP;
        if (isMobile) {
          retData.displaySongPlayer = parseInt(mobileDevice);
        } else {
          retData.displaySongPlayer = parseInt(desktopDevice);
        }
      } else {
        if (isMobile) {
          retData.displaySongPlayer = 0;
        } else {
          retData.displaySongPlayer = 0;
        }
      }
    })
    .catch((err) => {
      console.log(err);
    });
  return retData;
};

function encryptText(str) {
  var encoded = "";
  str = btoa(str);
  str = btoa(str);
  for (let i = 0; i < str.length; i++) {
    var a = str.charCodeAt(i);
    var b = a ^ 10; // bitwise XOR with any number, e.g. 123
    encoded = encoded + String.fromCharCode(b);
  }
  encoded = btoa(encoded);
  return encoded;
}
export {
  fetchAPIData,
  API_ROOT_PATH,
  getCarouselData,
  encryptText,
  checkForPrivateAlbum,
  updateVisitCounter,
  getAlbumVideosData,
  ALBUM_SONG_LOCATION_PATH,
  getAlbumSongsDisplaySettings,
  ALBUM_CAROUSEL_TYPE,
  isMobile,
};

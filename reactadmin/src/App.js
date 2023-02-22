import React, { useEffect, useState } from "react";
import { Route, BrowserRouter, Switch } from "react-router-dom";
import DisplaySettingsUpdate from "./components/DisplaySettingsUpdate";
import UpdateVideo from "./components/UpdateVideo";
import DisplaySettings from "./components/DisplaySettings";
import UpdateSitemap from "./components/UpdateSitemap";
import Login from "./components/Login";
import Protected from "./components/Protected";
import Logout from "./components/Logout";
import YTVideoList from "./components/YTVideoList";
import YTMonetization from "./components/YTMonetization";
import { Modal } from "react-bootstrap";
import CreateAlbum from "./components/CreateAlbum";
import AlbumList from "./components/AlbumList";
import EditAlbum from "./components/EditAlbum";
import AddNutrition from "./components/AddNutrition";
import AdminProfile from "./components/AdminProfile";
import UpdateDpAlbum from "./components/UpdateDpAlbum";
import AddVideoToAlbum from "./components/AddVideoToAlbum";
import AlbumAccess from "./components/AlbumAccess";
import AddAlbumSongs from "./components/AddAlbumSongs";

function App() {
  // sessionStorage.clear();
  const [show, setShow] = useState(false);
  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);

  useEffect(() => {
    // console.log("%c%s", css, "All Code Runs Happy");
    setInterval(() => {
      if (navigator.onLine) {
        console.log(
          "%c ONLINE ",
          "background: green; color: white; font-size: 20px; font-weight: bold"
        );
      } else {
        console.log(
          "%c OFFLINE ",
          "background: red; color: white; font-size: 20px; font-weight: bold"
        );
        handleShow();
      }
    }, 1000);
  }, []);

  return (
    <div style={{ textAlign: "center" }}>
      <a href="https://www.google.com">
        <Modal
          show={show}
          backdrop="static"
          onHide={handleClose}
          style={{ backgroundColor: "#191970" }}
          centered
        >
          <Modal.Header closeButton>
            <b>PLEASE CHECK YOUR INTERNET CONNECTION</b>
          </Modal.Header>
          <Modal.Body>
            <b>
              CHECK YOUR INTERNET CONNECTION AND THEN COME BACK TO USE THE ADMIN
              PANEL FOR DELICIOUS PAKWAN
            </b>
          </Modal.Body>
        </Modal>
      </a>
      <BrowserRouter>
        <Switch>
          <Route path="/logout">
            <Logout />
          </Route>
          {/* <Route path="/login" component={Login} /> */}
          <Protected
            exact
            path="/displaysettingsupdate/:id"
            component={DisplaySettingsUpdate}
          />
          {/* <Route exact path="/search" component={RestaurantSearch} /> */}
          <Protected exact path="/updatevideo" component={UpdateVideo} />
          <Protected
            exact
            path="/displaysettings"
            component={DisplaySettings}
          />
          <Protected exact path="/ytvideolist" component={YTVideoList} />
          <Protected exact path="/ytmonetization" component={YTMonetization} />
          <Protected exact path="/updatesitemap" component={UpdateSitemap} />
          <Protected exact path="/createalbum" component={CreateAlbum} />
          <Protected exact path="/albumlist" component={AlbumList} />
          <Protected exact path="/updatedpalbum" component={UpdateDpAlbum} />
          <Protected
            exact
            path="/editalbum/:foldertitle/:displaytitle/:id"
            component={EditAlbum}
          />
          <Protected exact path="/addnutrition" component={AddNutrition} />
          <Protected exact path="/adminprofile" component={AdminProfile} />
          <Protected
            exact
            path="/addvideotoalbum"
            component={AddVideoToAlbum}
          />
          <Protected
            exact
            path="/albumaccesssettings"
            component={AlbumAccess}
          />
          <Protected exact path="/addalbumsongs" component={AddAlbumSongs} />

          <Route path="/" render={(props) => <Login {...props} />}></Route>
        </Switch>
      </BrowserRouter>
    </div>
  );
}

export default App;

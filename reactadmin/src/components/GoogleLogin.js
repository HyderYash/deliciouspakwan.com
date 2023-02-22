import React, { useEffect, useState } from "react";
import { Button, Modal } from "react-bootstrap";
import { GoogleLogin } from "react-google-login";
import { useHistory } from "react-router-dom";
import { fetchAPIData, clientId } from "./Common";

function GoogleLoginComponent() {
  const [show, setShow] = useState();
  useEffect(() => {
    sessionStorage.setItem("CURRENT_CLICKED_PAGE_ID", 1);
  }, []);
  const { push } = useHistory();
  const responseGoogle = (response) => {
    if (response.profileObj.email === "yashsharma.karate@gmail.com") {
      sessionStorage.setItem("GoogleUserName", response.profileObj.name);
      sessionStorage.setItem("GoogleUserImage", response.profileObj.imageUrl);
      sessionStorage.setItem("GoogleUserEmail", response.profileObj.email);
      fetchAPIData("/api/menuitems/menu_items.php", "", "GET").then((json) => {
        if (json.status === "Success") {
          sessionStorage.setItem("menuItems", JSON.stringify(json));
        }
      });
      push("/displaysettings");
    } else {
      handleModal();
    }
  };
  const handleModal = () => {
    setShow(!show);
  };
  const closeFirstModal = () => {
    setShow(false);
  };
  return (
    <div>
      <Modal
        show={show}
        backdrop="static"
        onHide={() => {
          handleModal();
        }}
      >
        <Modal.Header closeButton>
          <b>Warning From GOOGLE</b>
        </Modal.Header>
        <Modal.Body>
          <b>Invalid Google Login!!!!!</b>
        </Modal.Body>
        <Modal.Footer>
          <Button
            variant="success"
            onClick={() => {
              closeFirstModal();
            }}
          >
            OK
          </Button>
        </Modal.Footer>
      </Modal>
      <GoogleLogin
        clientId={clientId}
        buttonText="Login With Google"
        onSuccess={responseGoogle}
        onFailure={responseGoogle}
        cookiePolicy={"single_host_origin"}
      />
    </div>
  );
}

export default GoogleLoginComponent;

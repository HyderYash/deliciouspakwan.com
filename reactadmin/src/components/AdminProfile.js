import React from "react";
import { Container } from "react-bootstrap";
import NavBarMenu from "./NavBarMenu";
import GoogleAdmin from "./GoogleAdmin";
import Admin from "./Admin";

export default function AdminProfile() {
  return (
    <div>
      <NavBarMenu />
      <Container className="mt-3 p-2 mb-3" fluid>
        {sessionStorage.getItem("GoogleUserName") ? <GoogleAdmin /> : <Admin />}
      </Container>
    </div>
  );
}

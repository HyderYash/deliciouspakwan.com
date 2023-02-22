import React from "react";
import { Navbar } from "react-bootstrap";
import { Link } from "react-router-dom";

function NavbarComponent() {
  return (
    <div>
      <Navbar
        expand="lg"
        style={{
          backgroundColor: "#212121",
        }}
      >
        <Navbar.Brand className="ml-2 text-white" as={Link} to="/">
          <b>Albums</b>
        </Navbar.Brand>
      </Navbar>
    </div>
  );
}

export default NavbarComponent;

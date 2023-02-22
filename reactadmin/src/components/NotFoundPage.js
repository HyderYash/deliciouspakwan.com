import React from "react";
import four0fourImg from "../assets/images/404.png";

const NotFoundPage = () => {
  return (
    <img
      src={four0fourImg}
      alt="404 Page"
      style={{ height: "100vh", width: "100vw", objectFit: "contain" }}
    ></img>
  );
};

export default NotFoundPage;

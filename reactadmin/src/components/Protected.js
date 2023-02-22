import React from "react";
import { Redirect, Route } from "react-router-dom";

const Protected = ({ component: Cmp, ...rest }) => (
  <Route
    {...rest}
    render={(props) =>
      sessionStorage.getItem("login") ||
      sessionStorage.getItem("GoogleUserName") ? (
        <Cmp {...props} />
      ) : (
        <Redirect to={{ pathname: "/", state: { from: props.location } }} />
      )
    }
  />
);

export default Protected;

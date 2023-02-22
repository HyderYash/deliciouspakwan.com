import React from "react";
import AdminHome from "./admin/AdminHome";
import UserHome from "./user/UserHome";
import Login from "./pages/Login";
import { Route, Switch } from "react-router";
import SignUp from "./pages/SignUp";
import { ThemeProvider } from "@material-ui/styles";
import { CssBaseline, createMuiTheme } from "@material-ui/core";
import AddService from "./admin/AddService";
import ProductSettings from "./user/ProductSettings";
import ProductOverView from "./user/ProductOverView";
import "react-toastify/dist/ReactToastify.css";
import ForgotPassword from "./pages/ForgotPassword";
import ChangePassword from "./pages/ChangePassword";
import UserManagement from "./admin/UserManagement";
import ProductReport from "./user/ProductReport";

const App = () => {
  const theme = createMuiTheme({
    palette: {
      type: "light",
    },
  });
  return (
    <div>
      <ThemeProvider theme={theme}>
        <CssBaseline />
        <Switch>
          <Route path="/" exact>
            <Login />
          </Route>
          <Route path="/signup" exact>
            <SignUp />
          </Route>
          <Route path="/forgotpassword" exact>
            <ForgotPassword />
          </Route>
          <Route path="/changePassword" exact>
            <ChangePassword />
          </Route>
          <Route path="/admin" exact>
            <AdminHome />
          </Route>
          <Route path="/usermanagement" exact>
            <UserManagement />
          </Route>

          <Route path="/user" exact>
            <UserHome />
          </Route>
          <Route path="/addservice" exact>
            <AddService />
          </Route>
          <Route path="/user/productoverview/:serviceid" exact>
            <ProductOverView />
          </Route>
          <Route path="/user/productsettings/:serviceid" exact>
            <ProductSettings />
          </Route>
          <Route path="/user/productreport/:serviceid" exact>
            <ProductReport />
          </Route>
        </Switch>
      </ThemeProvider>
    </div>
  );
};

export default App;

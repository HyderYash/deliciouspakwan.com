import { createMuiTheme, CssBaseline, ThemeProvider } from "@material-ui/core";
import React from "react";
import Home from "./pages/Home";
import AdminLogin from "./pages/AdminLogin";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";
import "react-toastify/dist/ReactToastify.css";
import AdminHomePage from "./pages/AdminHomePage";
import ManageLang from "./pages/ManageLang";
import { SiteInfo } from "./pages/SiteInfo";

const App = () => {
  const darkTheme = createMuiTheme({
    palette: {
      type: "dark",
    },
  });
  return (
    <div>
      <ThemeProvider theme={darkTheme}>
        <CssBaseline />
        <Router>
          <Switch>
            <Route path="/" exact>
              <Home />
            </Route>
            <Route path="/admin" exact>
              <AdminLogin />
            </Route>
            <Route path="/adminhome" exact>
              <AdminHomePage />
            </Route>
            <Route path="/manangelang" exact>
              <ManageLang />
            </Route>
            <Route path="/siteinfo" exact>
              <SiteInfo />
            </Route>
          </Switch>
        </Router>
      </ThemeProvider>
    </div>
  );
};

export default App;

import React, { useState } from "react";
import { makeStyles } from "@material-ui/core/styles";
import AppBar from "@material-ui/core/AppBar";
import clsx from "clsx";
import Toolbar from "@material-ui/core/Toolbar";
import Typography from "@material-ui/core/Typography";
import ExitToAppIcon from "@material-ui/icons/ExitToApp";
import IconButton from "@material-ui/core/IconButton";
import MenuIcon from "@material-ui/icons/Menu";
import SwipeableDrawer from "@material-ui/core/SwipeableDrawer";
import List from "@material-ui/core/List";
import ListItem from "@material-ui/core/ListItem";
import ListItemIcon from "@material-ui/core/ListItemIcon";
import ListItemText from "@material-ui/core/ListItemText";
import { Button, Divider } from "@material-ui/core";
import HomeIcon from "@material-ui/icons/Home";
import SupervisorAccountIcon from "@material-ui/icons/SupervisorAccount";
import { Link } from "react-router-dom";
import SettingsIcon from "@material-ui/icons/Settings";
import InfoIcon from "@material-ui/icons/Info";

const useStyles = makeStyles((theme) => ({
  root: {
    flexGrow: 1,
  },
  title: {
    flexGrow: 1,
  },
  list: {
    width: 250,
  },
  fullList: {
    width: "auto",
  },
  bottomPush: {
    position: "fixed",
    bottom: 0,
    textAlign: "center",
    paddingBottom: 10,
  },
}));
export default function AdminHome(props) {
  const classes = useStyles();
  const [state, setState] = useState({
    top: false,
    left: false,
    bottom: false,
    right: false,
  });
  const toggleDrawer = (anchor, open) => (event) => {
    if (
      event &&
      event.type === "keydown" &&
      (event.key === "Tab" || event.key === "Shift")
    ) {
      return;
    }

    setState({ ...state, [anchor]: open });
  };
  const list = (anchor) => (
    <div
      className={clsx(classes.list, {
        [classes.fullList]: anchor === "top" || anchor === "bottom",
      })}
      role="presentation"
      onClick={toggleDrawer(anchor, false)}
      onKeyDown={toggleDrawer(anchor, false)}
    >
      <List>
        <Link to="/" style={{ textDecoration: "none", color: "white" }}>
          <ListItem button>
            <ListItemIcon>
              <HomeIcon />
            </ListItemIcon>
            <ListItemText primary="Home" />
          </ListItem>
          <Divider />
        </Link>
        <Link to="/admin" style={{ textDecoration: "none", color: "white" }}>
          <ListItem button>
            <ListItemIcon>
              <SupervisorAccountIcon />
            </ListItemIcon>
            <ListItemText primary="Admin" />
          </ListItem>
          <Divider />
        </Link>
        {sessionStorage.getItem("adminToken") &&
        sessionStorage.getItem("adminLogin") &&
        sessionStorage.getItem("adminID") ? (
          <>
            <Link
              to="/siteinfo"
              style={{ textDecoration: "none", color: "white" }}
            >
              <ListItem button>
                <ListItemIcon>
                  <InfoIcon />
                </ListItemIcon>
                <ListItemText primary="Site Info" />
              </ListItem>
              <Divider />
            </Link>
            <a
              href="/manangelang"
              style={{ textDecoration: "none", color: "white" }}
            >
              <ListItem
                button
                onClick={() => sessionStorage.setItem("manageLang", "fe")}
              >
                <ListItemIcon>
                  <SettingsIcon />
                </ListItemIcon>
                <ListItemText primary="Manage FE Lang" />
              </ListItem>
              <Divider />
            </a>
            <a
              href="/manangelang"
              style={{ textDecoration: "none", color: "white" }}
            >
              <ListItem
                button
                onClick={() => sessionStorage.setItem("manageLang", "be")}
              >
                <ListItemIcon>
                  <SettingsIcon />
                </ListItemIcon>
                <ListItemText primary="Manage BE Lang" />
              </ListItem>
              <Divider />
            </a>
            <a
              href="/manangelang"
              style={{ textDecoration: "none", color: "white" }}
            >
              <ListItem
                button
                onClick={() => sessionStorage.setItem("manageLang", "api")}
              >
                <ListItemIcon>
                  <SettingsIcon />
                </ListItemIcon>
                <ListItemText primary="Manage API Lang" />
              </ListItem>
              <Divider />
            </a>
            <div className={classes.bottomPush}>
              <Button
                variant="contained"
                color="secondary"
                style={{ marginLeft: "10px" }}
                endIcon={<ExitToAppIcon />}
                onClick={() => {
                  sessionStorage.clear();
                  window.location.replace("/admin");
                }}
              >
                Logout
              </Button>
            </div>
          </>
        ) : null}
      </List>
    </div>
  );

  return (
    <div>
      <div className={classes.root}>
        <AppBar position="static">
          <Toolbar>
            <IconButton
              edge="start"
              className={classes.menuButton}
              color="inherit"
              aria-label="menu"
              onClick={toggleDrawer("left", true)}
              style={{ color: "white" }}
            >
              <MenuIcon />
            </IconButton>
            <Typography
              variant="h6"
              className={classes.title}
              style={{ color: "white" }}
            >
              {props.header}
            </Typography>
          </Toolbar>
          <SwipeableDrawer
            open={state["left"]}
            onClose={toggleDrawer("left", false)}
            onOpen={toggleDrawer("left", true)}
          >
            {list("left")}
          </SwipeableDrawer>
        </AppBar>
      </div>
    </div>
  );
}

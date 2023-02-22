import React, { forwardRef, useState } from "react";
import { makeStyles } from "@material-ui/core/styles";
import AppBar from "@material-ui/core/AppBar";
import clsx from "clsx";
import Toolbar from "@material-ui/core/Toolbar";
import Typography from "@material-ui/core/Typography";
import IconButton from "@material-ui/core/IconButton";
import MenuIcon from "@material-ui/icons/Menu";
import ExitToAppIcon from "@material-ui/icons/ExitToApp";
import SwipeableDrawer from "@material-ui/core/SwipeableDrawer";
import List from "@material-ui/core/List";
import ListItem from "@material-ui/core/ListItem";
import ListItemIcon from "@material-ui/core/ListItemIcon";
import ListItemText from "@material-ui/core/ListItemText";
import SubscriptionsIcon from "@material-ui/icons/Subscriptions";
import { deleteDailyReportCookies, getCookie } from "../components/Common";
import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  Divider,
  Slide,
  Tooltip,
} from "@material-ui/core";
import VpnKeyIcon from "@material-ui/icons/VpnKey";
import { Link } from "react-router-dom";
import PeopleIcon from "@material-ui/icons/People";
import HomeIcon from "@material-ui/icons/Home";
import CheckCircleIcon from "@material-ui/icons/CheckCircle";
import CancelIcon from "@material-ui/icons/Cancel";

const useStyles = makeStyles((theme) => ({
  root: {
    flexGrow: 1,
  },
  menuButton: {
    marginRight: theme.spacing(2),
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
const Transition = forwardRef(function Transition(props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});
export default function AdminHome(props) {
  const classes = useStyles();
  const [state, setState] = useState({
    top: false,
    left: false,
    bottom: false,
    right: false,
  });
  const [open, setOpen] = useState(false);

  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };
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
        {getCookie("userType") === "A" ? (
          <>
            <Link
              to="/admin"
              style={{ textDecoration: "none", color: "black" }}
            >
              <ListItem button>
                <ListItemIcon>
                  <HomeIcon />
                </ListItemIcon>
                <ListItemText primary="Home" />
              </ListItem>
            </Link>
            <Link
              to="/usermanagement"
              style={{ textDecoration: "none", color: "black" }}
            >
              <ListItem button>
                <ListItemIcon>
                  <PeopleIcon />
                </ListItemIcon>
                <ListItemText primary="User Management" />
              </ListItem>
            </Link>
          </>
        ) : (
          <Link to="/user" style={{ textDecoration: "none", color: "black" }}>
            <ListItem button>
              <ListItemIcon>
                <SubscriptionsIcon />
              </ListItemIcon>
              <ListItemText primary=" My Subscriptions" />
            </ListItem>
          </Link>
        )}

        <Link
          to="/changePassword"
          style={{ textDecoration: "none", color: "black" }}
        >
          <ListItem button>
            <ListItemIcon>
              <VpnKeyIcon />
            </ListItemIcon>
            <ListItemText primary="Change Password" />
          </ListItem>
        </Link>
        <Divider />
        <div className={classes.bottomPush}>
          <ListItem>
            <span
              style={{
                fontSize: "13px",
                textTransform: "capitalize",
                fontWeight: "bold",
              }}
            >{`Welcome ${getCookie("userName")}`}</span>
          </ListItem>
          <ListItem>
            <span
              style={{ fontSize: "10px", fontWeight: "bold" }}
            >{`Your Last Login: ${new Date(
              getCookie("userLastLogged")
            ).toLocaleDateString()}`}</span>
          </ListItem>
        </div>
      </List>
    </div>
  );

  return (
    <div>
      <div className={classes.root}>
        <Dialog open={open} TransitionComponent={Transition} keepMounted>
          <DialogTitle>Log Out</DialogTitle>
          <DialogContent>
            <DialogContentText id="alert-dialog-slide-description">
              You will be returned to the login screen
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button
              variant="contained"
              onClick={handleClose}
              autoFocus
              style={{ backgroundColor: "red", color: "white" }}
              startIcon={<CancelIcon />}
              required
            >
              Cancel
            </Button>
            <Button
              variant="contained"
              type="submit"
              autoFocus
              style={{ backgroundColor: "#388e3c", color: "white" }}
              startIcon={<CheckCircleIcon />}
              onClick={() => deleteDailyReportCookies()}
            >
              Log Out
            </Button>
          </DialogActions>
        </Dialog>
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
            {getCookie("userType") === "A" ? (
              <Tooltip title="Home">
                <Link to="/admin">
                  <IconButton>
                    <HomeIcon style={{ color: "white" }} />
                  </IconButton>
                </Link>
              </Tooltip>
            ) : (
              <Tooltip title="Home">
                <Link to="/user">
                  <IconButton>
                    <HomeIcon style={{ color: "white" }} />
                  </IconButton>
                </Link>
              </Tooltip>
            )}
            <Tooltip title="Logout">
              <IconButton onClick={() => handleClickOpen()}>
                <ExitToAppIcon style={{ color: "white" }} />
              </IconButton>
            </Tooltip>
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

import React, { useState } from "react";
import { makeStyles } from "@material-ui/core/styles";
import Card from "@material-ui/core/Card";
import CardHeader from "@material-ui/core/CardHeader";
import CardContent from "@material-ui/core/CardContent";
import Avatar from "@material-ui/core/Avatar";
import { Button, Grid, TextField } from "@material-ui/core";
import SendIcon from "@material-ui/icons/Send";
import Header from "../components/common/Header";
import { ToastContainer, toast } from "react-toastify";
import { fetchAPIData } from "../components/Common";
import { Redirect } from "react-router";

const useStyles = makeStyles((theme) => ({
  media: {
    height: 0,
    paddingTop: "56.25%", // 16:9
  },
  expand: {
    transform: "rotate(0deg)",
    marginLeft: "auto",
    transition: theme.transitions.create("transform", {
      duration: theme.transitions.duration.shortest,
    }),
  },
  expandOpen: {
    transform: "rotate(180deg)",
  },
  large: {
    width: theme.spacing(15),
    height: theme.spacing(15),
    display: "block",
    marginLeft: "auto",
    marginRight: "auto",
  },
}));

export default function AdminLogin() {
  const classes = useStyles();
  const [userName, setUserName] = useState("");
  const [password, setPassword] = useState("");
  const [showSnackbar, setShowSnackbar] = useState(false);
  const [redirect, setRedirect] = useState(false);
  const notifyError = (message) =>
    toast.error(message, {
      position: "bottom-left",
      autoClose: 5000,
      hideProgressBar: false,
      closeOnClick: true,
      pauseOnHover: true,
      draggable: true,
      progress: undefined,
    });
  const notifySuccess = (message) =>
    toast.success(message, {
      position: "bottom-left",
      autoClose: 5000,
      hideProgressBar: false,
      closeOnClick: true,
      pauseOnHover: true,
      draggable: true,
      progress: undefined,
    });
  const handleLogin = (e) => {
    e.preventDefault();
    const formData = {
      FN_NAME: "checkAdminLogin",
      ADMIN_NAME: userName,
      ADMIN_PASSWORD: password,
    };
    fetchAPIData("/", formData, "POST").then((json) => {
      if (json.records.status === "Success") {
        setShowSnackbar(true);
        setRedirect(true);
        notifySuccess(json.records.message);
        sessionStorage.setItem("adminToken", json.records.adminToken);
        sessionStorage.setItem("adminLogin", true);
        sessionStorage.setItem("adminID", json.records.adminID);
      } else {
        setShowSnackbar(true);
        notifyError(json.records.message);
      }
    });
    console.log(formData);
  };
  return (
    <div>
      <Header header="Admin Login" />
      {showSnackbar === true ? <ToastContainer /> : null}
      {redirect === true ? <Redirect to="/adminhome" /> : null}
      <Grid
        container
        spacing={0}
        direction="column"
        alignItems="center"
        justify="center"
        style={{ minHeight: "80vh" }}
      >
        <Grid item xs={3} style={{ maxWidth: "90%" }}>
          <Card
            style={{
              border: "1px solid grey",
              boxShadow: "6px 6px 6px 6px rgba(0, 0, 0, 0.6)",
              width: "100%",
            }}
          >
            <CardHeader title="Admin Login" />
            <Avatar
              alt="Remy Sharp"
              src="http://deliciouspakwan.com/images/img_avatar.png"
              className={classes.large}
            />
            <CardContent>
              <form onSubmit={handleLogin}>
                <TextField
                  label="Enter Username"
                  variant="outlined"
                  style={{ width: "100%", marginBottom: "10px" }}
                  onChange={(e) => setUserName(e.target.value)}
                  required
                />
                <TextField
                  label="Enter Password"
                  variant="outlined"
                  type="password"
                  style={{ width: "100%", marginBottom: "10px" }}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                />
                <Button
                  variant="contained"
                  color="primary"
                  type="submit"
                  endIcon={<SendIcon />}
                  style={{ width: "100%", marginBottom: "10px" }}
                >
                  Login
                </Button>
              </form>
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </div>
  );
}

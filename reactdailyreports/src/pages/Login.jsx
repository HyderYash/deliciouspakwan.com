import React, { useState } from "react";
import { makeStyles } from "@material-ui/core/styles";
import Card from "@material-ui/core/Card";
import CardHeader from "@material-ui/core/CardHeader";
import CardContent from "@material-ui/core/CardContent";
import Avatar from "@material-ui/core/Avatar";
import {
  Button,
  Checkbox,
  FormControlLabel,
  Grid,
  Paper,
  TextField,
} from "@material-ui/core";
import SendIcon from "@material-ui/icons/Send";
import { Link } from "react-router-dom";
import { API_ROOT_PATH, fetchAPIData } from "../components/Common";
import { ToastContainer, toast } from "react-toastify";
import { Alert } from "@material-ui/lab";

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
  extraAlertCss: {
    "& .MuiAlert-icon": {
      display: "flex",
      opacity: "0.9",
      padding: "3px 0",
      fontSize: "15px",
      marginRight: "12px",
    },
    "& .MuiAlert-message": {
      padding: "0px 0",
    },
  },
}));

export default function Login() {
  const classes = useStyles();
  const [userName, setUserName] = useState("");
  const [password, setPassword] = useState("");
  const [showSnackbar, setShowSnackbar] = useState(false);
  const [userType, setUserType] = useState("U");
  const notify = (message) =>
    toast.error(message, {
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
      uname: userName,
      psw: password,
      utype: userType,
    };
    console.log(formData);
    fetchAPIData(
      "/dailyreportsapi/login/checkLogin.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        writeCookie(json.message, json.userRec);
        console.log(userType);
        if (userType === "A") {
          window.location.replace("/admin");
        } else {
          window.location.replace("/user");
        }
      } else {
        notify(json.message);
        setShowSnackbar(true);
      }
    });
  };
  const writeCookie = (token, userRec) => {
    var now = new Date();
    now.setTime(now.getTime() + 1 * 3600 * 1000);
    document.cookie = `userToken=${token};expires=${now.toUTCString()};`;
    document.cookie = `userID=${userRec.ID};expires=${now.toUTCString()};`;
    document.cookie = `userName=${
      userRec.USER_NAME
    };expires=${now.toUTCString()};`;
    document.cookie = `userType=${
      userRec.USER_TYPE
    };expires=${now.toUTCString()};`;
    document.cookie = `userLastLogged=${
      userRec.USER_LAST_LOGGED
    };expires=${now.toUTCString()};`;
  };
  return (
    <div>
      {showSnackbar === true ? <ToastContainer /> : null}
      <Grid
        container
        spacing={0}
        direction="column"
        alignItems="center"
        justify="center"
        style={{ minHeight: "100vh" }}
      >
        <Grid item xs={3} style={{ maxWidth: "90%" }}>
          <Card
            style={{
              border: "1px solid grey",
              boxShadow: "6px 6px 6px 6px rgba(0, 0, 0, 0.6)",
              width: "100%",
            }}
          >
            <CardHeader title="Login" />
            <Avatar
              alt="Remy Sharp"
              src={`${API_ROOT_PATH}/images/img_avatar.png`}
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
                  autoComplete="off"
                />
                <TextField
                  label="Enter Password"
                  variant="outlined"
                  type="password"
                  style={{ width: "100%", marginBottom: "10px" }}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                  autoComplete="off"
                />
                <Alert
                  severity="info"
                  style={{
                    marginBottom: "10px",
                    height: "30px",
                    fontSize: "10px",
                    lineHeight: "20px",
                  }}
                  className={classes.extraAlertCss}
                >
                  Username & Password are Case Sensitive
                </Alert>
                <Button
                  variant="contained"
                  color="primary"
                  type="submit"
                  endIcon={<SendIcon />}
                  style={{ width: "100%", marginBottom: "10px" }}
                >
                  Login
                </Button>
                <FormControlLabel
                  control={
                    <Checkbox
                      color="primary"
                      onChange={(e) => setUserType(e.target.value)}
                    />
                  }
                  color="secondary"
                  label="Admin Login"
                  value="A"
                />
                <Paper
                  variant="outlined"
                  elevation={3}
                  style={{ padding: "15px", backgroundColor: "#f1f1f1" }}
                >
                  <Link to="/signup" style={{ textDecoration: "none" }}>
                    <Button variant="contained" color="secondary">
                      SignUp
                    </Button>
                  </Link>
                  <Link to="/forgotpassword">
                    <span
                      style={{
                        color: "#003399",
                        fontSize: "15px",
                        textAlign: "right",
                        float: "right",
                        marginTop: "10px",
                      }}
                    >
                      <strong>Forgot Password?</strong>
                    </span>
                  </Link>
                </Paper>
              </form>
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </div>
  );
}

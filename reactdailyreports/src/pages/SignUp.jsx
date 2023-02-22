import React, { useEffect, useState } from "react";
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
// import SnackbarComponent from "../components/Snackbar";
import { API_ROOT_PATH, fetchAPIData } from "../components/Common";
import { ToastContainer, toast } from "react-toastify";
import { Alert } from "@material-ui/lab";

const useStyles = makeStyles((theme) => ({
  root: {
    width: "20%",
    position: "absolute", //or relative
    top: "50%",
    left: "50%",
    transform: "translate(-50%, -50%)",
    border: "1px solid grey",
    boxShadow: "6px 6px 6px 6px rgba(0, 0, 0, 0.6)",
  },
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

export default function SignUp() {
  const [questionPart1, setQuestionPart1] = useState("");
  const [questionPart2, setQuestionPart2] = useState("");
  const [userName, setUserName] = useState("");
  const [password, setPassword] = useState("");
  const [email, setEmail] = useState("");
  const [answer, setAnswer] = useState("");
  const [showSnackbar, setShowSnackbar] = useState(false);
  const notify = (message) =>
    toast.info(message, {
      position: "bottom-left",
      autoClose: 5000,
      hideProgressBar: false,
      closeOnClick: true,
      pauseOnHover: true,
      draggable: true,
      progress: undefined,
    });
  const classes = useStyles();
  useEffect(() => {
    setQuestionPart1(Math.floor(Math.random() * (10 - 1)) + 1);
    setQuestionPart2(Math.floor(Math.random() * (10 - 1)) + 1);
  }, []);
  const handleSignUp = (e) => {
    e.preventDefault();
    console.log(answer);
    if (questionPart1 + questionPart2 === parseInt(answer)) {
      const formData = {
        uname: userName,
        psw: password,
        uemail: email,
      };
      fetchAPIData("/dailyreportsapi/login/signup.php", formData, "POST").then(
        (json) => {
          if (json.status === "Success") {
            setShowSnackbar(true);
            notify(json.message);
          } else {
            setShowSnackbar(true);
            notify(json.message);
          }
        }
      );
    } else {
      setShowSnackbar(true);
      notify("Enter Correct Answer");
    }
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
        style={{ minHeight: "100vh", marginTop: "10px", marginBottom: "30px" }}
      >
        <Grid item xs={3} style={{ maxWidth: "90%" }}>
          <Card
            style={{
              border: "1px solid grey",
              boxShadow: "6px 6px 6px 6px rgba(0, 0, 0, 0.6)",
              width: "100%",
            }}
          >
            <CardHeader
              title="SignUp"
              subheader="Last Login: September 14, 2016"
            />
            <Avatar
              alt="Remy Sharp"
              src={`${API_ROOT_PATH}/images/img_avatar.png`}
              className={classes.large}
            />
            <CardContent>
              <form onSubmit={handleSignUp}>
                <TextField
                  label="Enter Username"
                  type="text"
                  variant="outlined"
                  style={{ width: "100%", marginBottom: "10px" }}
                  required
                  onChange={(e) => setUserName(e.target.value)}
                />
                <TextField
                  label="Enter Password"
                  type="password"
                  variant="outlined"
                  required
                  style={{ width: "100%", marginBottom: "10px" }}
                  onChange={(e) => setPassword(e.target.value)}
                />
                <TextField
                  label="Enter Email"
                  type="email"
                  variant="outlined"
                  required
                  style={{ width: "100%", marginBottom: "10px" }}
                  onChange={(e) => setEmail(e.target.value)}
                />
                <span style={{ fontWeight: "bold" }}>Question</span>
                <Paper
                  elevation={1}
                  style={{
                    marginTop: "10px",
                    marginBottom: "10px",
                    padding: "15px",
                    textAlign: "center",
                    backgroundColor: "#ececec",
                  }}
                >
                  <span style={{ color: "black" }}>
                    {questionPart1} + {questionPart2}
                  </span>
                </Paper>
                <TextField
                  label="Enter Answer"
                  type="number"
                  required
                  variant="outlined"
                  style={{ width: "100%", marginBottom: "10px" }}
                  onChange={(e) => setAnswer(e.target.value)}
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
                  SignUp
                </Button>

                <FormControlLabel
                  control={<Checkbox color="primary" />}
                  label="Remember Me"
                />
                <Paper
                  variant="outlined"
                  elevation={3}
                  style={{ padding: "15px", backgroundColor: "#f1f1f1" }}
                >
                  <Link to="/" style={{ textDecoration: "none" }}>
                    <Button variant="contained" color="secondary">
                      Login
                    </Button>
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

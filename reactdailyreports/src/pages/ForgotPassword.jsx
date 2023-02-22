import React, { useState } from "react";
import Card from "@material-ui/core/Card";
import CardHeader from "@material-ui/core/CardHeader";
import CardContent from "@material-ui/core/CardContent";
import { Button, Grid, Paper, TextField } from "@material-ui/core";
import SendIcon from "@material-ui/icons/Send";
import { Link } from "react-router-dom";
import { fetchAPIData } from "../components/Common";
import { ToastContainer, toast } from "react-toastify";

export default function ForgotPassword() {
  const [emailAddress, setEmailAddress] = useState("");
  const [showSnackbar, setShowSnackbar] = useState(false);
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
  const handleSubmit = (e) => {
    e.preventDefault();
    const formData = {
      EMAIL_ADDRESS: emailAddress,
    };
    fetchAPIData(
      "/dailyreportsapi/login/forgotPassword.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        notifySuccess(json.message);
        setShowSnackbar(true);
      } else {
        notifyError(json.message);
        setShowSnackbar(true);
      }
    });
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
            <CardHeader title="Forgot Password?" />
            <CardContent>
              <form onSubmit={handleSubmit}>
                <TextField
                  label="Enter Email Address"
                  variant="outlined"
                  type="email"
                  style={{ width: "100%", marginBottom: "10px" }}
                  onChange={(e) => setEmailAddress(e.target.value)}
                  required
                />
                <Button
                  variant="contained"
                  color="primary"
                  type="submit"
                  endIcon={<SendIcon />}
                  style={{ width: "100%", marginBottom: "10px" }}
                >
                  Send Email
                </Button>
                <Paper
                  variant="outlined"
                  elevation={3}
                  style={{ padding: "15px", backgroundColor: "#f1f1f1" }}
                >
                  <Grid
                    container
                    spacing={3}
                    style={{
                      display: "flex",
                      alignItems: "center",
                      justifyContent: "center",
                    }}
                  >
                    <Link to="/signup" style={{ textDecoration: "none" }}>
                      <Button
                        variant="contained"
                        color="secondary"
                        style={{ marginTop: "5px", marginBottom: "5px" }}
                      >
                        SignUp
                      </Button>
                    </Link>
                    <Link to="/" style={{ textDecoration: "none" }}>
                      <Button
                        variant="contained"
                        color="secondary"
                        style={{
                          marginTop: "5px",
                          marginBottom: "5px",
                          marginLeft: "10px",
                        }}
                      >
                        Login
                      </Button>
                    </Link>
                  </Grid>
                </Paper>
              </form>
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </div>
  );
}

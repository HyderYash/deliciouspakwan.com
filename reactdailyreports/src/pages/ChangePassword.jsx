import React, { useState } from "react";
import Stepper from "@material-ui/core/Stepper";
import Step from "@material-ui/core/Step";
import StepLabel from "@material-ui/core/StepLabel";
import Header from "../components/Header";
import Card from "@material-ui/core/Card";
import CardHeader from "@material-ui/core/CardHeader";
import CardContent from "@material-ui/core/CardContent";
import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  Grid,
  TextField,
  useMediaQuery,
  useTheme,
} from "@material-ui/core";
import SendIcon from "@material-ui/icons/Send";
import {
  fetchAPIData,
  getCookie,
  deleteDailyReportCookies,
} from "../components/Common";
import { ToastContainer, toast } from "react-toastify";
import CheckCircleIcon from "@material-ui/icons/CheckCircle";

function getSteps() {
  return ["Enter Current Password", "Enter New Password"];
}

export default function ChangePassword() {
  const [activeStep, setActiveStep] = useState(0);
  const [skipped, setSkipped] = useState(new Set());
  const [currentPassword, setCurrentPassword] = useState("");
  const [newPassword, setNewPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [showSnackbar, setShowSnackbar] = useState(false);
  const [openDialog, setOpenDialog] = useState(false);
  const theme = useTheme();
  const fullScreen = useMediaQuery(theme.breakpoints.down("sm"));
  const steps = getSteps();
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

  const isStepSkipped = (step) => {
    return skipped.has(step);
  };

  const handleNext = () => {
    console.log(activeStep);
    if (activeStep < 1) {
      let newSkipped = skipped;
      if (isStepSkipped(activeStep)) {
        newSkipped = new Set(newSkipped.values());
        newSkipped.delete(activeStep);
      }

      setActiveStep((prevActiveStep) => prevActiveStep + 1);
      setSkipped(newSkipped);
    }
  };
  const handleBack = () => {
    if (activeStep !== 0) {
      setActiveStep((prevActiveStep) => prevActiveStep - 1);
    }
  };
  const step1PasswordCheck = (e) => {
    e.preventDefault();
    handleNext();
  };
  const handleSubmit = (e) => {
    e.preventDefault();
    const formData = {
      CURRENT_PASSWORD: currentPassword,
      NEW_PASSWORD: newPassword,
      USER_ID: getCookie("userID"),
    };
    if (confirmPassword === newPassword) {
      fetchAPIData(
        "/dailyreportsapi/login/checkAndChangePassword.php",
        formData,
        "POST"
      ).then((json) => {
        if (json.status === "Success") {
          notifySuccess(json.message);
          setShowSnackbar(true);
          setOpenDialog(true);
        } else {
          handleBack();
          notifyError(json.message);
          setShowSnackbar(true);
        }
      });
    } else {
      notifyError("Password and Confirm Password Does not match");
      setShowSnackbar(true);
    }
  };

  return (
    <div>
      <Header header="Change Password" />
      {showSnackbar === true ? <ToastContainer /> : null}
      <Dialog
        fullScreen={fullScreen}
        open={openDialog}
        aria-labelledby="responsive-dialog-title"
      >
        <DialogTitle
          disableTypography
          style={{
            display: "flex",
            justifyContent: "space-between",
            alignItems: "center",
          }}
        >
          <h2>Your Password has been changed</h2>
        </DialogTitle>
        <DialogContent>
          <DialogContentText>
            Click Ok to login with your new password
          </DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button
            variant="contained"
            autoFocus
            style={{ backgroundColor: "#388e3c", color: "white" }}
            startIcon={<CheckCircleIcon />}
            onClick={() => deleteDailyReportCookies()}
          >
            Ok
          </Button>
        </DialogActions>
      </Dialog>
      <Stepper activeStep={activeStep}>
        {steps.map((label, index) => {
          const stepProps = {};
          const labelProps = {};
          if (isStepSkipped(index)) {
            stepProps.completed = false;
          }
          return (
            <Step key={label} {...stepProps}>
              <StepLabel {...labelProps}>{label}</StepLabel>
            </Step>
          );
        })}
      </Stepper>
      <div>
        {activeStep === 0 ? (
          <Grid
            container
            spacing={0}
            direction="column"
            alignItems="center"
            justify="center"
            style={{ minHeight: "80vh" }}
          >
            <Grid item xs={3}>
              <Card
                style={{
                  padding: "10px",
                  boxShadow: "5px 5px 5px 5px rgba(0, 0, 0, 0.5)",
                }}
              >
                <CardHeader title="Enter Your Current Password" />
                <CardContent>
                  <form onSubmit={step1PasswordCheck}>
                    <TextField
                      label="Enter Your Current Password"
                      variant="outlined"
                      value={currentPassword}
                      type="password"
                      style={{ width: "100%", marginBottom: "10px" }}
                      onChange={(e) => setCurrentPassword(e.target.value)}
                      required
                    />
                    <Button
                      variant="contained"
                      color="primary"
                      type="submit"
                      endIcon={<SendIcon />}
                      style={{ width: "100%", marginBottom: "10px" }}
                    >
                      Next
                    </Button>
                  </form>
                </CardContent>
              </Card>
            </Grid>
          </Grid>
        ) : null}
        {activeStep === 1 ? (
          <Grid
            container
            spacing={0}
            direction="column"
            alignItems="center"
            justify="center"
            style={{ minHeight: "80vh" }}
          >
            <Grid item xs={3}>
              <Card
                style={{
                  padding: "10px",
                  boxShadow: "5px 5px 5px 5px rgba(0, 0, 0, 0.5)",
                }}
              >
                <CardHeader title="Enter Your New Password" />
                <CardContent>
                  <form onSubmit={handleSubmit}>
                    <TextField
                      label="Enter Your New Password"
                      variant="outlined"
                      type="password"
                      value={newPassword}
                      style={{ width: "100%", marginBottom: "10px" }}
                      onChange={(e) => setNewPassword(e.target.value)}
                      required
                    />
                    <TextField
                      label="Confirm New Password"
                      variant="outlined"
                      type="password"
                      value={confirmPassword}
                      style={{ width: "100%", marginBottom: "10px" }}
                      onChange={(e) => setConfirmPassword(e.target.value)}
                      required
                    />
                    <Button
                      variant="contained"
                      color="primary"
                      type="submit"
                      endIcon={<SendIcon />}
                      style={{ width: "100%", marginBottom: "10px" }}
                    >
                      Change
                    </Button>
                  </form>
                </CardContent>
              </Card>
            </Grid>
          </Grid>
        ) : null}
      </div>
    </div>
  );
}

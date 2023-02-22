import React, { useEffect, useState } from "react";
import Header from "../components/Header";
import { fetchAPIData, getCookie } from "../components/Common";
import { Divider, Grid, Paper, Typography } from "@material-ui/core";
import Loader from "../components/Loader";
import List from "@material-ui/core/List";
import ListItem from "@material-ui/core/ListItem";
import ListItemText from "@material-ui/core/ListItemText";
import { makeStyles } from "@material-ui/core/styles";
import ListItemSecondaryAction from "@material-ui/core/ListItemSecondaryAction";
import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  IconButton,
} from "@material-ui/core";
import CloseIcon from "@material-ui/icons/Close";
import CheckCircleIcon from "@material-ui/icons/CheckCircle";
import CancelIcon from "@material-ui/icons/Cancel";
import AddCircleIcon from "@material-ui/icons/AddCircle";
import { Link } from "react-router-dom";
import { ToastContainer, toast } from "react-toastify";

const useStyles = makeStyles((theme) => ({
  root: {
    width: "100%",
    backgroundColor: theme.palette.background.paper,
  },
  modal: {
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
  },
  paper: {
    backgroundColor: theme.palette.background.paper,
    border: "2px solid #000",
    boxShadow: theme.shadows[5],
    padding: "2rem",
  },
  dialogTitle: {
    display: "flex",
    justifyContent: "space-between",
    alignItems: "center",
  },
}));
const UserHome = () => {
  const classes = useStyles();
  const [serviceUserSubscribe, setServiceUserSubscribe] = useState([]);
  const [subscribeNowList, setSubscribeNowList] = useState([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [openUnSubscribeDialogModal, setOpenUnSubscribeDialogModal] =
    useState(false);
  const [ID, setID] = useState("");
  const [unsubscribeID, setUnsubscribeID] = useState("");
  const [showSnackbar, setShowSnackbar] = useState(false);
  const [refreshAPIData, setRefreshAPIData] = useState(false);
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

  const subscribeToService = () => {
    handleClose();
    setLoading(true);
    const formData = {
      USER_ID: getCookie("userID"),
      SERVICE_ID: ID,
    };
    fetchAPIData(
      "/dailyreportsapi/services/subscribeUserToService.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        setRefreshAPIData(true);
        console.log(json);
        setLoading(false);
        setShowSnackbar(true);
        notify(json.message);
      } else {
        setRefreshAPIData(true);
        setLoading(false);
        setShowSnackbar(true);
        notify(json.message);
      }
    });
  };
  const unSubscribeToService = () => {
    handleClickCloseUnsubscribeDialog();
    setLoading(true);
    const formData = {
      USER_ID: getCookie("userID"),
      SERVICE_ID: unsubscribeID,
    };
    fetchAPIData(
      "/dailyreportsapi/services/unSubscribeUserToService.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        setRefreshAPIData(true);
        console.log(json);
        setLoading(false);
        setShowSnackbar(true);
        notify(json.message);
      } else {
        setRefreshAPIData(true);
        setLoading(false);
        setShowSnackbar(true);
        notify(json.message);
      }
    });
  };
  const handleClickOpenUnsubscribeDialog = () => {
    setOpenUnSubscribeDialogModal(true);
  };
  const handleClickCloseUnsubscribeDialog = () => {
    setOpenUnSubscribeDialogModal(false);
  };
  const handleClickOpen = () => {
    setOpen(true);
  };
  const handleClose = () => {
    setOpen(false);
  };
  const getServiceUserSubscribe = () => {
    if (getCookie("userID") !== "") {
      const formData = {
        USER_ID: getCookie("userID"),
      };
      fetchAPIData(
        "/dailyreportsapi/services/getServiceUserSubscribe.php",
        formData,
        "POST"
      ).then((json) => {
        if (json.status === "Success") {
          console.log(json);
          setServiceUserSubscribe(json.records);
        }
      });
    }
  };
  const getSubscribeNowList = () => {
    const formData = {
      USER_ID: getCookie("userID"),
    };
    fetchAPIData(
      "/dailyreportsapi/services/getServiceUserSubscribeNow.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        setSubscribeNowList(json.records);
        console.log(json);
        setLoading(false);
      } else {
        setLoading(false);
      }
    });
  };
  useEffect(() => {
    getServiceUserSubscribe();
    getSubscribeNowList();
    setRefreshAPIData(false);
  }, [refreshAPIData]);

  return (
    <div>
      {showSnackbar === true ? <ToastContainer /> : null}
      <Header header="User Home" />
      {loading ? <Loader /> : null}
      <Grid container spacing={1} style={{ width: "100%" }}>
        <Grid item xs={12} sm={12} md={6} lg={6}>
          <Paper
            elevation={2}
            style={{
              margin: "10px",
              padding: "10px",
              boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
            }}
          >
            <Typography variant="h5" style={{ textAlign: "center" }}>
              <strong>Your Subscriptions</strong>
            </Typography>
            <Divider style={{ marginTop: "10px", marginBottom: "10px" }} />
            <Dialog
              open={openUnSubscribeDialogModal}
              aria-labelledby="responsive-dialog-title"
            >
              <DialogTitle disableTypography className={classes.dialogTitle}>
                <h2>Are you sure you want to Unsubscribe to this Serivce?</h2>
                <IconButton onClick={handleClickCloseUnsubscribeDialog}>
                  <CloseIcon />
                </IconButton>
              </DialogTitle>
              <DialogContent>
                <DialogContentText>Click Agree to Continue</DialogContentText>
              </DialogContent>
              <DialogActions>
                <Button
                  variant="contained"
                  onClick={handleClickCloseUnsubscribeDialog}
                  autoFocus
                  style={{ backgroundColor: "red", color: "white" }}
                  startIcon={<CancelIcon />}
                >
                  Cancel
                </Button>
                <Button
                  variant="contained"
                  onClick={() => unSubscribeToService()}
                  autoFocus
                  style={{ backgroundColor: "#388e3c", color: "white" }}
                  startIcon={<CheckCircleIcon />}
                >
                  Agree
                </Button>
              </DialogActions>
            </Dialog>
            <List className={classes.root}>
              {serviceUserSubscribe.length > 0 ? (
                serviceUserSubscribe.map((item, key) => (
                  <ListItem button key={key}>
                    <Link
                      to={`/user/productoverview/${item.ID}`}
                      style={{ textDecoration: "none", color: "black" }}
                    >
                      <ListItemText
                        style={{ textDecoration: "underline" }}
                        primary={item.SERVICE_NAME}
                      />
                    </Link>
                    <ListItemSecondaryAction>
                      <Button
                        variant="contained"
                        color="secondary"
                        endIcon={<CancelIcon />}
                        onClick={() => {
                          handleClickOpenUnsubscribeDialog();
                          setUnsubscribeID(item.ID);
                        }}
                      >
                        UnSubscribe
                      </Button>
                    </ListItemSecondaryAction>
                  </ListItem>
                ))
              ) : (
                <h4>You have not Subscribed any Services</h4>
              )}
            </List>
          </Paper>
        </Grid>
        <Grid item xs={12} sm={12} md={6} lg={6}>
          <Paper
            elevation={3}
            style={{
              margin: "10px",
              padding: "10px",
              boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
            }}
          >
            <Typography variant="h5" style={{ textAlign: "center" }}>
              <strong>Subscribe Now</strong>
            </Typography>
            <Divider style={{ marginTop: "10px", marginBottom: "10px" }} />
            <Dialog open={open} aria-labelledby="responsive-dialog-title">
              <DialogTitle disableTypography className={classes.dialogTitle}>
                <h2>Are you sure you want to subscribe to this Serivce?</h2>
                <IconButton onClick={handleClose}>
                  <CloseIcon />
                </IconButton>
              </DialogTitle>
              <DialogContent>
                <DialogContentText>Click Agree to Continue</DialogContentText>
              </DialogContent>
              <DialogActions>
                <Button
                  variant="contained"
                  onClick={handleClose}
                  autoFocus
                  style={{ backgroundColor: "red", color: "white" }}
                  startIcon={<CancelIcon />}
                >
                  Cancel
                </Button>
                <Button
                  variant="contained"
                  onClick={() => subscribeToService()}
                  autoFocus
                  style={{ backgroundColor: "#388e3c", color: "white" }}
                  startIcon={<CheckCircleIcon />}
                >
                  Agree
                </Button>
              </DialogActions>
            </Dialog>
            <List className={classes.root}>
              {subscribeNowList.length > 0 ? (
                subscribeNowList.map((item, key) => (
                  <ListItem key={key}>
                    <ListItemText primary={item.SERVICE_NAME} />
                    <ListItemSecondaryAction>
                      <Button
                        variant="contained"
                        color="secondary"
                        onClick={() => {
                          handleClickOpen();
                          setID(item.ID);
                        }}
                        endIcon={<AddCircleIcon />}
                      >
                        Subscribe
                      </Button>
                    </ListItemSecondaryAction>
                  </ListItem>
                ))
              ) : (
                <h4>No Data to Display</h4>
              )}
            </List>
          </Paper>
        </Grid>
      </Grid>
    </div>
  );
};

export default UserHome;

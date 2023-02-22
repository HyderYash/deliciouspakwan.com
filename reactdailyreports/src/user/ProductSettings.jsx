import React, { useEffect, useState } from "react";
import Header from "../components/Header";
import { Link } from "react-router-dom";
import {
  Backdrop,
  Button,
  CircularProgress,
  Grid,
  IconButton,
  Paper,
  TextField,
  Tooltip,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
} from "@material-ui/core";
import AddCircleIcon from "@material-ui/icons/AddCircle";
import CancelIcon from "@material-ui/icons/Cancel";
import SendIcon from "@material-ui/icons/Send";
import { fetchAPIData, getCookie } from "../components/Common";
import { makeStyles } from "@material-ui/core/styles";
import VisibilityIcon from "@material-ui/icons/Visibility";
import { ToastContainer, toast } from "react-toastify";
import { Alert, AlertTitle } from "@material-ui/lab";
import CheckCircleIcon from "@material-ui/icons/CheckCircle";

const useStyles = makeStyles((theme) => ({
  backdrop: {
    zIndex: theme.zIndex.drawer + 1,
    color: "#fff",
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
    dialogTitle: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center",
    },
  },
}));

function ProductSettings() {
  const classes = useStyles();
  const [inputList, setInputList] = useState([{ ID: "", PRODUCT_NAME: "" }]);
  const [serviceName, setServiceName] = useState("");
  const [fieldToDelete, setFieldToDelete] = useState("");
  const [loading, setLoading] = useState(false);
  const [showSnackbar, setShowSnackbar] = useState(false);
  const [openDialog, setOpenDialog] = useState(false);

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

  useEffect(() => {
    setLoading(true);
    getServiceNameById(window.location.href.split("/")[5]);
    const formData = {
      SERVICE_ID: window.location.href.split("/")[5],
      USER_ID: getCookie("userID"),
    };
    fetchAPIData(
      "/dailyreportsapi/services/getProductsInfo.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        if (json.records !== false) {
          setInputList(json.records);
        }
        setLoading(false);
      }
    });
  }, []);
  console.log(inputList);
  const getServiceNameById = (serviceId) => {
    const formData = {
      SERVICE_ID: serviceId,
    };
    fetchAPIData(
      "/dailyreportsapi/services/getServiceNameByID.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        setServiceName(`${json.records[0].SERVICE_NAME}`);
      }
    });
    console.log(formData);
  };

  // handle input change
  const handleInputChange = (e, index) => {
    const { name, value } = e.target;
    if (name === "ServiceColumn") {
      const list = [...inputList];
      list[index][name] = value;
      setInputList(list);
    } else {
      const list = [...inputList];
      list[index][name] = value;
      setInputList(list);
    }
  };

  // handle click event of the Remove button
  const handleRemoveClick = (index) => {
    const list = [...inputList];
    list.splice(index, 1);
    setInputList(list);
    setOpenDialog(false);
  };

  // handle click event of the Add button
  const handleAddClick = () => {
    setInputList([...inputList, { PRODUCT_NAME: "" }]);
  };
  const handleSubmit = (e) => {
    e.preventDefault();
    setLoading(true);
    const formData = {
      USER_ID: getCookie("userID"),
      SERVICE_ID: window.location.href.split("/")[5],
      PRODUCTINFO: inputList,
    };
    setLoading(false);
    console.log(formData);
    fetchAPIData(
      "/dailyreportsapi/services/userProductSettings.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        setLoading(false);
        setShowSnackbar(true);
        notify(json.message);
      } else {
        setLoading(false);
        setShowSnackbar(true);
        notify(json.message);
      }
    });
  };
  const openDialogAndSetFieldToDelete = (index) => {
    setFieldToDelete(index);
    setOpenDialog(true);
  };
  const closeDialog = () => {
    setOpenDialog(false);
  };

  return (
    <div>
      {loading ? (
        <Backdrop className={classes.backdrop} open={loading}>
          <CircularProgress color="inherit" />
        </Backdrop>
      ) : null}
      <Dialog open={openDialog} aria-labelledby="responsive-dialog-title">
        <DialogTitle disableTypography className={classes.dialogTitle}>
          <h2>Info</h2>
        </DialogTitle>
        <DialogContent>
          <DialogContentText>
            All your data from this product will be deleted... Click Ok to
            continue
          </DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button
            variant="contained"
            onClick={() => closeDialog()}
            autoFocus
            style={{ backgroundColor: "red", color: "white" }}
            startIcon={<CancelIcon />}
          >
            Cancel
          </Button>
          <Button
            variant="contained"
            onClick={() => handleRemoveClick(fieldToDelete)}
            autoFocus
            style={{ backgroundColor: "#388e3c", color: "white" }}
            startIcon={<CheckCircleIcon />}
          >
            Agree
          </Button>
        </DialogActions>
      </Dialog>
      {showSnackbar === true ? <ToastContainer /> : null}
      <Header header="Product Settings" />
      <Link to={`/user/productoverview/${window.location.href.split("/")[5]}`}>
        <Button
          variant="contained"
          color="primary"
          style={{ margin: "10px", float: "right" }}
          endIcon={<VisibilityIcon />}
        >
          Product Overview
        </Button>
      </Link>
      <form onSubmit={handleSubmit}>
        <Paper
          elevation={3}
          style={{
            margin: "10px",
            padding: "10px",
            marginTop: "100px",
            boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
          }}
        >
          <Alert severity="success" icon={false} style={{ width: "50%" }}>
            <AlertTitle
              style={{
                fontSize: "1.7rem",
                fontWeight: "bold",
                marginBottom: "0px",
              }}
            >
              {serviceName}
            </AlertTitle>
          </Alert>
          <p
            style={{
              marginBottom: "20px",
              color: "red",
              fontSize: "0.7rem",
            }}
          >
            <em>Note: Please enter a Product to view Product Overview</em>
          </p>
          {inputList.map((x, i) => {
            return (
              <div key={i} style={{ display: "flex" }}>
                <Grid container spacing={1} style={{ marginBottom: "1rem" }}>
                  <Grid item xs={12} sm={12} md={6} lg={6}>
                    <TextField
                      name="PRODUCT_NAME"
                      value={x.PRODUCT_NAME}
                      variant="outlined"
                      label="Product Name"
                      onChange={(e) => handleInputChange(e, i)}
                      style={{ width: "100%" }}
                      autoComplete="off"
                      required
                    />
                  </Grid>
                  <Grid item xs={12} sm={12} md={6} lg={6}>
                    <div className="btn-box">
                      {inputList.length !== 1 && (
                        <>
                          <Tooltip title="Delete Row">
                            <IconButton
                              onClick={() => {
                                openDialogAndSetFieldToDelete(i);
                              }}
                            >
                              <CancelIcon
                                fontSize="large"
                                style={{ color: "red" }}
                              />
                            </IconButton>
                          </Tooltip>
                        </>
                      )}
                      {inputList.length - 1 === i && (
                        <>
                          <Tooltip title="Add Row">
                            <IconButton onClick={handleAddClick}>
                              <AddCircleIcon fontSize="large" />
                            </IconButton>
                          </Tooltip>
                        </>
                      )}
                    </div>
                  </Grid>
                </Grid>
              </div>
            );
          })}
          <Button
            variant="contained"
            color="primary"
            type="submit"
            style={{ marginTop: "10px" }}
            endIcon={<SendIcon />}
          >
            Submit
          </Button>
        </Paper>
      </form>
    </div>
  );
}

export default ProductSettings;

import React, { useState } from "react";
import Header from "../components/Header";
import SupervisorAccountIcon from "@material-ui/icons/SupervisorAccount";
import { Link } from "react-router-dom";
import {
  Backdrop,
  Button,
  CircularProgress,
  FormControlLabel,
  Grid,
  // IconButton,
  // InputLabel,
  // MenuItem,
  Paper,
  Radio,
  RadioGroup,
  // Select,
  TextField,
} from "@material-ui/core";
// import AddCircleIcon from "@material-ui/icons/AddCircle";
// import CancelIcon from "@material-ui/icons/Cancel";
import SendIcon from "@material-ui/icons/Send";
import { fetchAPIData } from "../components/Common";
import { makeStyles } from "@material-ui/core/styles";
import { ToastContainer, toast } from "react-toastify";

const useStyles = makeStyles((theme) => ({
  backdrop: {
    zIndex: theme.zIndex.drawer + 1,
    color: "#fff",
  },
}));

function AddService() {
  const classes = useStyles();

  // const [inputList, setInputList] = useState([
  //   { ServiceColumn: "", ServiceColumnType: "" },
  // ]);
  const [serviceName, setServiceName] = useState("");
  const [status, setStatus] = useState("Y");
  const [loading, setLoading] = useState(false);
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

  // const buildServiceColumnName = (name) => {
  //   var buildName = name;
  //   var buildNamePart1 = buildName.toUpperCase();
  //   var buildNamePart2 = buildNamePart1.replace(/ /g, "_");
  //   var FINAL_BUILD_NAME = buildNamePart2;
  //   return FINAL_BUILD_NAME;
  // };
  // handle input change
  // const handleInputChange = (e, index) => {
  //   const { name, value } = e.target;
  //   if (name === "ServiceColumn") {
  //     const finalBuildServiceColumn = buildServiceColumnName(value);
  //     const list = [...inputList];
  //     list[index][name] = finalBuildServiceColumn;
  //     setInputList(list);
  //   } else {
  //     const list = [...inputList];
  //     list[index][name] = value;
  //     setInputList(list);
  //   }
  // };

  // // handle click event of the Remove button
  // const handleRemoveClick = (index) => {
  //   const list = [...inputList];
  //   list.splice(index, 1);
  //   setInputList(list);
  // };

  // // handle click event of the Add button
  // const handleAddClick = () => {
  //   setInputList([...inputList, { ServiceColumn: "", ServiceColumnType: "" }]);
  // };
  const handleSubmit = (e) => {
    e.preventDefault();
    setLoading(true);
    const formData = {
      SERVICE_NAME: serviceName,
      STATUS: status,
    };
    fetchAPIData(
      "/dailyreportsapi/services/addServices.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        notify(json.message);
        setLoading(false);
        setShowSnackbar(true);
      } else {
        setLoading(false);
        setShowSnackbar(true);
        notify(json.message);
      }
    });
    console.log(formData);
  };

  return (
    <div>
      {loading ? (
        <Backdrop className={classes.backdrop} open={loading}>
          <CircularProgress color="inherit" />
        </Backdrop>
      ) : null}
      {showSnackbar === true ? <ToastContainer /> : null}
      <Header header="Add Service" />
      <Link to="/admin">
        <Button
          variant="contained"
          color="secondary"
          style={{ margin: "10px", float: "right" }}
          endIcon={<SupervisorAccountIcon />}
        >
          Services
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
          <Grid container>
            <Grid item xs={12} sm={6} md={6} lg={6}>
              <span style={{ fontSize: "2rem", fontWeight: "bold" }}>
                Add Service
              </span>
            </Grid>
            <Grid item xs={12} sm={6} md={6} lg={6}>
              <RadioGroup row style={{ float: "right" }}>
                <FormControlLabel
                  value="Y"
                  control={<Radio required={true} />}
                  label="Active"
                  checked={status === "Y"}
                  onChange={(e) => setStatus(e.target.value)}
                />
                <FormControlLabel
                  value="N"
                  control={<Radio required={true} />}
                  label="Paused"
                  checked={status === "N"}
                  onChange={(e) => setStatus(e.target.value)}
                />
              </RadioGroup>
            </Grid>
          </Grid>

          <TextField
            label="Service Name"
            variant="outlined"
            style={{ width: "100%", marginTop: "15px" }}
            onChange={(e) => setServiceName(e.target.value)}
            required
          />
          {/* {inputList.map((x, i) => {
            return (
              <div key={i} style={{ display: "flex" }}>
                <Grid container spacing={1}>
                  <Grid item xs={12} sm={12} md={4} lg={4}>
                    <InputLabel
                      style={{
                        marginTop: "15px",
                        marginBottom: "10px",
                      }}
                    >
                      Service Column
                    </InputLabel>
                    <TextField
                      name="ServiceColumn"
                      value={x.ServiceColumn}
                      variant="outlined"
                      onChange={(e) => handleInputChange(e, i)}
                      style={{ width: "100%" }}
                      autoComplete="off"
                      required
                    />
                  </Grid>
                  <Grid item xs={12} sm={12} md={4} lg={4}>
                    <InputLabel
                      style={{
                        marginTop: "15px",
                        marginBottom: "10px",
                      }}
                    >
                      Service Data Type
                    </InputLabel>
                    <Select
                      variant="outlined"
                      labelId="demo-simple-select-label"
                      name="ServiceColumnType"
                      value={x.ServiceColumnType}
                      onChange={(e) => handleInputChange(e, i)}
                      label="Data Type"
                      style={{
                        width: "100%",
                      }}
                      required
                    >
                      <MenuItem value="INT">INT</MenuItem>
                      <MenuItem value="FLOAT">FLOAT</MenuItem>
                      <MenuItem value="VARCHAR">VARCHAR</MenuItem>
                      <MenuItem value="ENUM">ENUM</MenuItem>
                      <MenuItem value="DATETIME">DATETIME</MenuItem>
                    </Select>
                    <TextField
                      name="ServiceColumnType"
                      value={x.ServiceColumnType}
                      variant="outlined"
                      label="Service Data Type"
                      onChange={(e) => handleInputChange(e, i)}
                      style={{
                        width: "100%",
                        marginTop: "15px",
                      }}
                      autoComplete="off"
                      required
                    /> 
                  </Grid>
                  <Grid item xs={12} sm={12} md={4} lg={4}>
                    <div className="btn-box">
                      {inputList.length !== 1 && (
                        <>
                          <IconButton
                            style={{ marginTop: "35px" }}
                            onClick={() => {
                              handleRemoveClick(i);
                            }}
                          >
                            <CancelIcon
                              fontSize="large"
                              style={{ color: "red" }}
                            />
                          </IconButton>
                        </>
                      )}
                      {inputList.length - 1 === i && (
                        <>
                          <IconButton
                            style={{ marginTop: "35px" }}
                            onClick={handleAddClick}
                          >
                            <AddCircleIcon fontSize="large" />
                          </IconButton>
                        </>
                      )}
                    </div>
                  </Grid>
                </Grid>
              </div>
            );
          })} */}
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

export default AddService;

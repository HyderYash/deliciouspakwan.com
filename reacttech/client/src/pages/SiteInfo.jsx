import React, { useEffect, useState } from "react";
import { authenticateAdmin, fetchAPIData } from "../components/Common";
import Header from "../components/common/Header";
import {
  Button,
  DialogContentText,
  FormControlLabel,
  Grid,
  MenuItem,
  Paper,
  Radio,
  RadioGroup,
  TextField,
} from "@material-ui/core";
import SiteInfoTable from "../components/SiteInfoTable";
import AddCircleIcon from "@material-ui/icons/AddCircle";
import Dialog from "@material-ui/core/Dialog";
import DialogActions from "@material-ui/core/DialogActions";
import DialogContent from "@material-ui/core/DialogContent";
import DialogTitle from "@material-ui/core/DialogTitle";
import Slide from "@material-ui/core/Slide";
import CheckCircleIcon from "@material-ui/icons/CheckCircle";
import CancelIcon from "@material-ui/icons/Cancel";
import Loader from "../components/Loader";

const Transition = React.forwardRef(function Transition(props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});

export const SiteInfo = () => {
  const [siteInfoRows, setSiteInfoRows] = useState([]);
  const [open, setOpen] = useState(false);
  const [feFormState, setFeFormState] = useState({
    feData: [],
  });
  const [beFormState, setBeFormState] = useState({
    beData: [],
  });
  const [apiFormState, setAPIFormState] = useState({
    apiData: [],
  });
  const [feData, setFeData] = useState([]);
  const [beData, setBeData] = useState([]);
  const [apIData, setApIData] = useState([]);
  const [siteName, setSiteName] = useState("");
  const [siteURL, setSiteURL] = useState("");
  const [siteDesc, setSiteDesc] = useState("");
  const [siteStatus, setSiteStatus] = useState("Y");
  const [openConfirmationDialog, setOpenConfirmationDialog] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleFeDataChange = (event) => {
    event.persist();
    setFeFormState((feFormState) => ({
      ...feFormState,
      [event.target.name]:
        event.target.type === "checkbox"
          ? event.target.checked
          : event.target.value,
    }));
  };
  const handleBeDataChange = (event) => {
    event.persist();
    setBeFormState((beFormState) => ({
      ...beFormState,
      [event.target.name]:
        event.target.type === "checkbox"
          ? event.target.checked
          : event.target.value,
    }));
  };
  const handleApiDataChange = (event) => {
    event.persist();
    setAPIFormState((apiFormState) => ({
      ...apiFormState,
      [event.target.name]:
        event.target.type === "checkbox"
          ? event.target.checked
          : event.target.value,
    }));
  };

  useEffect(() => {
    authenticateAdmin();
    getDataForTech();
    const formData = {
      FN_NAME: "getSiteInfo",
    };
    fetchAPIData("/", formData, "POST").then((json) => {
      setSiteInfoRows(json.records);
      const feFormData = {
        FN_NAME: "getDropDownLang",
        techType: "fe",
      };
      var tempfeJson = [];
      fetchAPIData("/", feFormData, "POST").then((json) => {
        json.records.forEach((feItem) => {
          tempfeJson.push(feItem.ID);
        });
      });
      const beFormData = {
        FN_NAME: "getDropDownLang",
        techType: "be",
      };
      var tempbeJson = [];
      fetchAPIData("/", beFormData, "POST").then((json) => {
        json.records.forEach((beItem) => {
          tempbeJson.push(beItem.ID);
        });
      });
      const apiFormData = {
        FN_NAME: "getDropDownLang",
        techType: "api",
      };
      var tempapiJson = [];
      fetchAPIData("/", apiFormData, "POST").then((json) => {
        json.records.forEach((apiItem) => {
          tempapiJson.push(apiItem.ID);
        });
        setLoading(false);
      });
      setFeFormState({
        feData: tempfeJson,
      });
      setBeFormState({
        beData: tempbeJson,
      });
      setAPIFormState({
        apiData: tempapiJson,
      });
    });
  }, []);
  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };
  const getDataForTech = () => {
    console.clear();
    const feFormData = { techType: "fe", FN_NAME: "getTechTypeData" };
    fetchAPIData("/", feFormData, "POST").then((json) => {
      setFeData(json.records);
      const beFormData = { techType: "be", FN_NAME: "getTechTypeData" };
      fetchAPIData("/", beFormData, "POST").then((json) => {
        setBeData(json.records);
        const apiFormData = { techType: "api", FN_NAME: "getTechTypeData" };
        fetchAPIData("/", apiFormData, "POST").then((json) => {
          setApIData(json.records);
        });
      });
    });
  };
  const handleSubmit = (e) => {
    e.preventDefault();
    setLoading(true);
    const formData = {
      FN_NAME: "saveSiteData",
      SITE_NAME: siteName,
      SITE_DESC: siteDesc,
      SITE_URL: siteURL,
      SITE_FE: covertArrToIntValArr(feFormState.feData),
      SITE_BE: covertArrToIntValArr(beFormState.beData),
      SITE_API: covertArrToIntValArr(apiFormState.apiData),
      SITE_STATUS: siteStatus,
    };
    console.clear();
    fetchAPIData("/", formData, "POST").then((json) => {
      setLoading(false);
      handleClose();
      funopenConfirmationDialog();
    });
  };
  const covertArrToIntValArr = (arr) => {
    var result = arr.map(function (x) {
      return parseInt(x, 10);
    });
    return result;
  };
  const funopenConfirmationDialog = () => {
    setOpenConfirmationDialog(true);
  };
  return (
    <div>
      <Header header="Site Info" />
      {loading ? <Loader /> : null}
      <Dialog
        open={openConfirmationDialog}
        TransitionComponent={Transition}
        keepMounted
      >
        <DialogTitle>You Data has been saved</DialogTitle>
        <DialogContent>
          <DialogContentText id="alert-dialog-slide-description">
            Click Ok to Continue
          </DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button
            variant="contained"
            type="submit"
            autoFocus
            style={{ backgroundColor: "#388e3c", color: "white" }}
            startIcon={<CheckCircleIcon />}
            onClick={() => {
              window.location.reload();
            }}
          >
            Ok
          </Button>
        </DialogActions>
      </Dialog>
      <Dialog
        open={open}
        TransitionComponent={Transition}
        keepMounted
        aria-labelledby="alert-dialog-slide-title"
        aria-describedby="alert-dialog-slide-description"
      >
        <form onSubmit={handleSubmit}>
          <DialogTitle id="alert-dialog-slide-title">Add Sites</DialogTitle>
          <DialogContent>
            <TextField
              label="Site Name"
              variant="outlined"
              type="text"
              required
              style={{
                width: "100%",
                marginTop: "10px",
                marginBottom: "10px",
              }}
              onChange={(e) => setSiteName(e.target.value)}
            />
            <TextField
              label="Site URL"
              variant="outlined"
              required
              type="url"
              style={{
                width: "100%",
                marginTop: "10px",
                marginBottom: "10px",
              }}
              onChange={(e) => setSiteURL(e.target.value)}
            />
            <TextField
              label="Site Desc"
              variant="outlined"
              required
              type="text"
              style={{
                width: "100%",
                marginTop: "10px",
                marginBottom: "10px",
              }}
              onChange={(e) => setSiteDesc(e.target.value)}
            />

            <Grid container spacing={3}>
              <Grid item xs={12} sm={12} md={4} lg={4}>
                <TextField
                  select
                  name="feData"
                  id="feData"
                  variant="outlined"
                  label="Front End"
                  required
                  autoFocus
                  SelectProps={{
                    multiple: true,
                    value: feFormState.feData,
                    onChange: handleFeDataChange,
                  }}
                  style={{ width: "100%" }}
                >
                  {feData.map((item, key) => (
                    <MenuItem value={item.ID} key={key}>
                      {item.LANG_NAME}
                    </MenuItem>
                  ))}
                </TextField>
              </Grid>
              <Grid item xs={12} sm={12} md={4} lg={4}>
                <TextField
                  select
                  name="beData"
                  id="beData"
                  variant="outlined"
                  label="Back End"
                  autoFocus
                  required
                  SelectProps={{
                    multiple: true,
                    value: beFormState.beData,
                    onChange: handleBeDataChange,
                  }}
                  style={{ width: "100%" }}
                >
                  {beData.map((item, key) => (
                    <MenuItem value={item.ID} key={key}>
                      {item.LANG_NAME}
                    </MenuItem>
                  ))}
                </TextField>
              </Grid>
              <Grid item xs={12} sm={12} md={4} lg={4}>
                <TextField
                  select
                  name="apiData"
                  id="apiData"
                  variant="outlined"
                  label="API"
                  required
                  autoFocus
                  SelectProps={{
                    multiple: true,
                    value: apiFormState.apiData,
                    onChange: handleApiDataChange,
                  }}
                  style={{ width: "100%" }}
                >
                  {apIData.map((item, key) => (
                    <MenuItem value={item.ID} key={key}>
                      {item.LANG_NAME}
                    </MenuItem>
                  ))}
                </TextField>
              </Grid>
            </Grid>
            <RadioGroup
              row
              style={{
                width: "100%",
                marginTop: "10px",
                marginBottom: "10px",
              }}
            >
              <FormControlLabel
                value="Y"
                control={<Radio required={true} />}
                checked={siteStatus === "Y"}
                onChange={(e) => setSiteStatus(e.target.value)}
                label="Active"
              />
              <FormControlLabel
                value="N"
                control={<Radio required={true} />}
                label="Paused"
                checked={siteStatus === "N"}
                onChange={(e) => setSiteStatus(e.target.value)}
              />
            </RadioGroup>
          </DialogContent>

          <DialogActions style={{ marginTop: "10px", marginBottom: "10px" }}>
            <Button
              variant="contained"
              autoFocus
              style={{ backgroundColor: "red", color: "white" }}
              startIcon={<CancelIcon />}
              onClick={() => handleClose()}
            >
              Cancel
            </Button>
            <Button
              variant="contained"
              type="submit"
              autoFocus
              style={{ backgroundColor: "#388e3c", color: "white" }}
              startIcon={<CheckCircleIcon />}
            >
              Submit
            </Button>
          </DialogActions>
        </form>
      </Dialog>
      <Paper
        elevation={2}
        style={{
          margin: "10px",
          padding: "10px",
          boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
          marginTop: "20px",
          backgroundColor: "#9e9090",
        }}
      >
        <Button
          variant="contained"
          color="primary"
          startIcon={<AddCircleIcon />}
          style={{ float: "right" }}
          onClick={() => handleClickOpen()}
        >
          Add Sites
        </Button>
        <SiteInfoTable rows={siteInfoRows} />
      </Paper>
    </div>
  );
};

import React, { useState } from "react";
import MaterialTable from "material-table";
import Button from "@material-ui/core/Button";
import Dialog from "@material-ui/core/Dialog";
import DialogActions from "@material-ui/core/DialogActions";
import DialogContent from "@material-ui/core/DialogContent";
import DialogTitle from "@material-ui/core/DialogTitle";
import Slide from "@material-ui/core/Slide";
import { buildCurrentDateFormat, fetchAPIData } from "./Common";
import {
  DialogContentText,
  FormControlLabel,
  Grid,
  MenuItem,
  Radio,
  RadioGroup,
  TextField,
  Typography,
} from "@material-ui/core";
import CheckCircleIcon from "@material-ui/icons/CheckCircle";
import CancelIcon from "@material-ui/icons/Cancel";
import Loader from "./Loader";

const Transition = React.forwardRef(function Transition(props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});

export default function SiteInfoTable(props) {
  const [openDialog, setOpenDialog] = useState(false);
  const [adminSelectedId, setAdminSelectedId] = useState("");
  const [inputSiteName, setInputSiteName] = useState("");
  const [inputSiteURL, setInputSiteURL] = useState("");
  const [inputSiteDesc, setInputSiteDesc] = useState("");
  const [inputSiteStatus, setInputSiteStatus] = useState("");
  const [inputSiteLastUpdated, setInputSiteLastUpdated] = useState("");
  const [loading, setLoading] = useState(false);
  const [confirmDialog, setConfirmDialog] = useState(false);
  const [feData, setFeData] = useState([]);
  const [beData, setBeData] = useState([]);
  const [apIData, setApIData] = useState([]);
  const [feFormState, setFeFormState] = useState({
    feData: [],
  });
  const [beFormState, setBeFormState] = useState({
    beData: [],
  });
  const [apiFormState, setAPIFormState] = useState({
    apiData: [],
  });

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

  const columns = [
    { title: "ID", field: "ID", width: "10%", editable: "false" },
    { title: "SITE NAME", field: "SITE_NAME" },
    { title: "SITE URL", field: "SITE_URL" },
    { title: "FRONT END", field: "FE_LANG" },
    { title: "BACK END", field: "BE_LANG" },
    { title: "APIs", field: "API_LANG" },
    { title: "STATUS", field: "SITE_STATUS", lookup: { Y: "Y", N: "N" } },
    { title: "LAST UPDATED", field: "SITE_LAST_UPDATED" },
  ];

  const rows = props.rows;
  const handleOpenDialog = () => {
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
  };
  const getDataById = (id) => {
    const formData = {
      FN_NAME: "getSiteDataByID",
      ID: id,
    };
    setAdminSelectedId(id);
    fetchAPIData("/", formData, "POST").then((json) => {
      var records = json.records[0];
      setInputSiteName(records.SITE_NAME);
      setInputSiteURL(records.SITE_URL);
      setInputSiteDesc(records.SITE_DESC);
      setInputSiteStatus(records.SITE_STATUS);
      setInputSiteLastUpdated(records.SITE_LAST_UPDATED);
      getDataForTech(
        records.SITE_FE.split(","),
        records.SITE_BE.split(","),
        records.SITE_API.split(",")
      );
      setFeFormState({
        feData: covertArrToIntValArr(records.SITE_FE.split(",")),
      });
      setBeFormState({
        beData: covertArrToIntValArr(records.SITE_BE.split(",")),
      });
      setAPIFormState({
        apiData: covertArrToIntValArr(records.SITE_API.split(",")),
      });
    });
  };
  const getDataForTech = (fe, be, api) => {
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
          setLoading(false);
        });
      });
    });
  };
  const handleSubmitModalData = (e) => {
    e.preventDefault();
    setLoading(true);
    const formData = {
      FN_NAME: "saveSiteInfoDetails",
      ID: adminSelectedId,
      SITE_NAME: inputSiteName,
      SITE_DESC: inputSiteDesc,
      SITE_URL: inputSiteURL,
      SITE_FE: covertArrToIntValArr(feFormState.feData),
      SITE_BE: covertArrToIntValArr(beFormState.beData),
      SITE_API: covertArrToIntValArr(apiFormState.apiData),
      SITE_STATUS: inputSiteStatus,
    };
    console.clear();
    fetchAPIData("/", formData, "POST").then((json) => {
      handleCloseDialog();
      setLoading(false);
      console.log(json);
      openConfirmDialog();
    });
    //
  };
  const covertArrToIntValArr = (arr) => {
    var result = arr.map(function (x) {
      return parseInt(x, 10);
    });
    return result;
  };
  const openConfirmDialog = () => {
    setConfirmDialog(true);
  };
  return (
    <div>
      {loading ? <Loader /> : null}
      <Dialog open={confirmDialog} TransitionComponent={Transition} keepMounted>
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
        open={openDialog}
        TransitionComponent={Transition}
        keepMounted
        aria-labelledby="alert-dialog-slide-title"
        aria-describedby="alert-dialog-slide-description"
      >
        <form onSubmit={handleSubmitModalData}>
          <DialogTitle id="alert-dialog-slide-title">
            Edit Site Info By ID ({adminSelectedId})
          </DialogTitle>
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
              value={inputSiteName}
              onChange={(e) => setInputSiteName(e.target.value)}
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
              value={inputSiteURL}
              onChange={(e) => setInputSiteURL(e.target.value)}
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
              value={inputSiteDesc}
              onChange={(e) => setInputSiteDesc(e.target.value)}
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
                label="Active"
                checked={inputSiteStatus === "Y"}
                onChange={(e) => setInputSiteStatus(e.target.value)}
              />
              <FormControlLabel
                value="N"
                control={<Radio required={true} />}
                label="Paused"
                checked={inputSiteStatus === "N"}
                onChange={(e) => setInputSiteStatus(e.target.value)}
              />
            </RadioGroup>
            <Typography>
              Last Updated: {buildCurrentDateFormat(inputSiteLastUpdated)}
            </Typography>
          </DialogContent>
          <DialogActions>
            <Button
              variant="contained"
              autoFocus
              style={{ backgroundColor: "red", color: "white" }}
              startIcon={<CancelIcon />}
              required
              onClick={() => handleCloseDialog()}
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
      <MaterialTable
        title="Updating Site Info Master"
        style={{
          margin: "10px",
          padding: "10px",
          boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
          marginTop: "50px",
        }}
        columns={columns}
        data={rows}
        actions={[
          {
            icon: "edit",
            onClick: (event, rowData) => {
              setLoading(true);
              getDataById(rowData.ID);
              handleOpenDialog(true);
            },
          },
        ]}
        options={{
          exportButton: true,
          grouping: true,
          actionsColumnIndex: -1,
        }}
      />
    </div>
  );
}

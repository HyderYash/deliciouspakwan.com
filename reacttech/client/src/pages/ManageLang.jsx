import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  Paper,
  Slide,
  TextField,
} from "@material-ui/core";
import React, { useEffect, useState } from "react";
import { authenticateAdmin, fetchAPIData } from "../components/Common";
import Header from "../components/common/Header";
import ManageLangTable from "../components/ManageLangTable";
import Loader from "../components/Loader";
import AddCircleIcon from "@material-ui/icons/AddCircle";
import CheckCircleIcon from "@material-ui/icons/CheckCircle";
import CancelIcon from "@material-ui/icons/Cancel";
const Transition = React.forwardRef(function Transition(props, ref) {
  return <Slide direction="up" ref={ref} {...props} />;
});

const ManageLang = () => {
  const [manageTableRows, setManageTableRows] = useState([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [langName, setLangName] = useState("");
  useEffect(() => {
    authenticateAdmin();
    var techType = sessionStorage.getItem("manageLang");
    const formData = {
      FN_NAME: "manageLang",
      techType: techType,
    };
    fetchAPIData("/", formData, "POST").then((json) => {
      setManageTableRows(json.records);
      setLoading(false);
    });
  }, []);
  const handleClickOpen = () => {
    setOpen(true);
  };

  const handleClose = () => {
    setOpen(false);
  };
  const handleSubmit = (e) => {
    e.preventDefault();
    setLoading(true);
    const formData = {
      FN_NAME: "saveLangData",
      techType: sessionStorage.getItem("manageLang"),
      LANG_NAME: langName,
    };
    fetchAPIData("/", formData, "POST").then((json) => {
      console.log(json);
      setLoading(false);
    });
    console.log(formData);
  };
  return (
    <div>
      <Header header="Manage Lang" />
      {loading ? <Loader /> : null}
      <Dialog open={open} TransitionComponent={Transition} keepMounted>
        <form onSubmit={handleSubmit}>
          <DialogTitle>Enter Lang Name</DialogTitle>
          <DialogContent>
            <TextField
              label="Language Name"
              variant="outlined"
              type="text"
              required
              style={{
                width: "100%",
                marginTop: "10px",
                marginBottom: "10px",
              }}
              onChange={(e) => setLangName(e.target.value)}
            />
          </DialogContent>
          <DialogActions>
            <Button
              variant="contained"
              autoFocus
              style={{ backgroundColor: "red", color: "white" }}
              startIcon={<CancelIcon />}
              required
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
              onClick={() => window.location.reload()}
            >
              Ok
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
          Add Languages
        </Button>
        <ManageLangTable rows={manageTableRows} />
      </Paper>
    </div>
  );
};

export default ManageLang;

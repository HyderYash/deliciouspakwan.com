import {
  Button,
  Dialog,
  DialogActions,
  DialogContent,
  DialogTitle,
  Divider,
  Grid,
  IconButton,
  InputLabel,
  makeStyles,
  MenuItem,
  Paper,
  Select,
  TextField,
  Typography,
} from "@material-ui/core";
import React, { useEffect, useState } from "react";
import { Link, Redirect } from "react-router-dom";
import Header from "../components/Header";
import SettingsIcon from "@material-ui/icons/Settings";
import { fetchAPIData, getCookie } from "../components/Common";
import Loader from "../components/Loader";
import EditIcon from "@material-ui/icons/Edit";
import CheckCircleIcon from "@material-ui/icons/CheckCircle";
import CancelIcon from "@material-ui/icons/Cancel";
import ReceiptIcon from "@material-ui/icons/Receipt";

const useStyles = makeStyles((theme) => ({
  dialogTitle: {
    display: "flex",
    justifyContent: "space-between",
    alignItems: "center",
  },
}));

const ProductOverView = () => {
  const classes = useStyles();
  const [serviceID, setServiceID] = useState("");
  const [months, setMonths] = useState([]);
  const [years, setYears] = useState([]);
  const [monthData, setMonthData] = useState([]);
  const [userSelectedMonthID, setUserSelectedMonthID] = useState(
    new Date().getMonth()
  );
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [redirect, setRedirect] = useState(false);
  const [userSelectedDate, setUserSelectedDate] = useState("");
  const [userSelectedProductName, setUserSelectedProductName] = useState("");
  const [userSelectedProductQNT, setUserSelectedProductQNT] = useState("");
  const [userSelectedProductAMT, setUserSelectedProductAMT] = useState("");
  const [userSelectedProductID, setUserSelectedProductID] = useState("");
  useEffect(() => {
    setServiceID(window.location.href.split("/")[5]);
    sessionStorage.setItem("YEARID", new Date().getFullYear());
    const productsSettingsData = {
      USER_ID: parseInt(getCookie("userID")),
      SERVICE_ID: parseInt(window.location.href.split("/")[5]),
    };
    fetchAPIData(
      "/dailyreportsapi/services/checkUserProductsSettings.php",
      productsSettingsData,
      "POST"
    ).then((json) => {
      console.log(json);
      if (json.status === "Failed") {
        setRedirect(true);
      } else {
        var monthId = "";
        if (sessionStorage.getItem("MONTHID")) {
          monthId = parseInt(sessionStorage.getItem("MONTHID")) + 1;
        } else {
          monthId = new Date().getMonth() + 1;
        }
        const formData = {
          MONTH: monthId,
          YEAR: 2021,
          USER_ID: parseInt(getCookie("userID")),
          SERVICE_ID: parseInt(window.location.href.split("/")[5]),
        };
        fetchAPIData(
          "/dailyreportsapi/services/getProductDetails.php",
          formData,
          "POST"
        ).then((json) => {
          console.log(json);
          setMonthData(json.records);
          setLoading(false);
        });
        var monthNames = [
          "Jan",
          "Feb",
          "Mar",
          "Apr",
          "May",
          "Jun",
          "Jul",
          "Aug",
          "Sep",
          "Oct",
          "Nov",
          "Dec",
        ];
        var tempMonth = [];
        var monthi = 0;
        monthNames.forEach((item) => {
          monthi++;
          tempMonth.push({ monthID: monthi - 1, monthName: item });
        });
        var tempYears = [];

        const tempyear = listOfYears(2021);
        tempyear.forEach((item) => {
          tempYears.push({ yearName: item });
        });
        setMonths(tempMonth);
        setYears(tempYears);
      }
    });
  }, [userSelectedMonthID]);

  const listOfYears = (startYear) => {
    var currentYear = new Date().getFullYear(),
      years = [];
    while (startYear <= currentYear) {
      years.push(startYear++);
    }

    return years;
  };
  function buildCurrentDateFormat(date) {
    var monthNames = [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ];
    var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    var today = new Date(date);
    var dd = today.getDate();
    var mm = monthNames[today.getMonth()];
    var yyyy = today.getFullYear();
    var day = days[today.getDay()];
    today = `${day}, ${dd} ${mm} ${yyyy}`;
    return today;
  }
  const handleOpenEditDialog = () => {
    setOpen(true);
  };
  const handleCloseEditDialog = () => {
    setOpen(false);
  };
  const setProductsInfo = (
    date,
    productName,
    productQNT,
    productAMT,
    productID
  ) => {
    setUserSelectedDate(date);
    setUserSelectedProductName(productName);
    setUserSelectedProductQNT(productQNT);
    setUserSelectedProductAMT(productAMT);
    setUserSelectedProductID(productID);
    handleOpenEditDialog();
  };
  const sendProductInfoToAPI = (e) => {
    e.preventDefault();
    const formData = {
      ENTRY_DATE: userSelectedDate,
      PRODUCT_NAME: userSelectedProductName,
      PRODUCT_QNT: userSelectedProductQNT,
      PRODUCT_AMT: userSelectedProductAMT,
      USER_ID: getCookie("userID"),
      SERVICE_ID: serviceID,
      PRODUCT_ID: userSelectedProductID,
    };
    fetchAPIData(
      "/dailyreportsapi/services/saveUserProductDetails.php",
      formData,
      "POST"
    ).then((json) => {
      console.log(json);
      if (json.status === "Success") {
        window.location.reload();
      }
    });
  };
  return (
    <div>
      {redirect === true ? (
        <Redirect
          to={`/user/productsettings/${window.location.href.split("/")[5]}`}
        />
      ) : null}
      <Dialog open={open} aria-labelledby="responsive-dialog-title">
        <DialogTitle disableTypography className={classes.dialogTitle}>
          <h2>
            Editing{" "}
            <span style={{ color: "blue" }}>{userSelectedProductName}</span> on{" "}
            <span style={{ color: "red" }}>
              {buildCurrentDateFormat(userSelectedDate)}
            </span>
          </h2>
        </DialogTitle>
        <form onSubmit={sendProductInfoToAPI}>
          <DialogContent style={{ height: "12rem" }}>
            <Grid container spacing={3}>
              <Grid item xs={12} sm={6} md={6} lg={6}>
                <TextField
                  label="Qnt"
                  variant="outlined"
                  style={{ width: "100%" }}
                  value={userSelectedProductQNT}
                  type="number"
                  step="0.01"
                  onChange={(e) => setUserSelectedProductQNT(e.target.value)}
                  required
                />
              </Grid>
              <Grid item xs={12} sm={6} md={6} lg={6}>
                <TextField
                  label="Amt"
                  variant="outlined"
                  style={{ width: "100%" }}
                  type="number"
                  step="0.01"
                  value={userSelectedProductAMT}
                  onChange={(e) => setUserSelectedProductAMT(e.target.value)}
                  required
                />
              </Grid>
            </Grid>
          </DialogContent>
          <DialogActions>
            <Button
              variant="contained"
              onClick={handleCloseEditDialog}
              autoFocus
              style={{ backgroundColor: "red", color: "white" }}
              startIcon={<CancelIcon />}
              required
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
      <Header header="Product Overview" />
      {loading ? <Loader /> : null}
      <Link to={`/user/productsettings/${serviceID}`}>
        <Button
          variant="contained"
          color="primary"
          style={{ margin: "10px", float: "right" }}
          endIcon={<SettingsIcon />}
        >
          Product Settings
        </Button>
      </Link>
      <Link to={`/user/productreport/${serviceID}`}>
        <Button
          variant="contained"
          color="primary"
          style={{ margin: "10px", float: "right" }}
          endIcon={<ReceiptIcon />}
        >
          Reports
        </Button>
      </Link>
      <Paper
        elevation={2}
        style={{
          margin: "10px",
          padding: "10px",
          boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
          marginTop: "80px",
        }}
      >
        <div style={{ display: "flex" }}>
          <div style={{ marginBottom: "10px" }}>
            <InputLabel
              style={{
                marginTop: "15px",
                marginBottom: "10px",
              }}
            >
              Select Month
            </InputLabel>
            <Select
              id="mon"
              label="Month"
              variant="outlined"
              defaultValue={
                sessionStorage.getItem("MONTHID")
                  ? sessionStorage.getItem("MONTHID")
                  : new Date().getMonth()
              }
              onChange={(e) => {
                setUserSelectedMonthID(e.target.value);
                setLoading(true);
                sessionStorage.setItem("MONTHID", e.target.value);
              }}
            >
              {months.map((item, key) => (
                <MenuItem value={item.monthID} key={key}>
                  {item.monthName}
                </MenuItem>
              ))}
            </Select>
          </div>

          <div style={{ marginBottom: "10px", marginLeft: "10px" }}>
            <InputLabel
              style={{
                marginTop: "15px",
                marginBottom: "10px",
              }}
            >
              Select Year
            </InputLabel>
            <Select
              id="monYear"
              label="Year"
              variant="outlined"
              defaultValue={new Date().getFullYear()}
            >
              {years.map((item, key) => (
                <MenuItem value={item.yearName} key={key}>
                  {item.yearName}
                </MenuItem>
              ))}
            </Select>
          </div>
        </div>
        <Grid container spacing={1}>
          {monthData.map((items, key) =>
            Object.keys(items).map((i, k) => (
              <Grid item xs={12} sm={2} md={2} lg={2} key={key++}>
                <Paper
                  style={{
                    marginTop: "10px",
                    padding: "10px",
                    boxShadow: "2px 2px 2px 2px rgba(0, 0, 0, 0.2)",
                    width: "100%",
                  }}
                  key={k++}
                >
                  <Typography variant="subtitle1">
                    <strong>{buildCurrentDateFormat(i)}</strong>
                  </Typography>
                  <Divider style={{ backgroundColor: "grey" }} />
                  {items[i].map((a, b) => (
                    <div key={b}>
                      <Paper
                        style={{
                          marginTop: "5px",
                          padding: "10px 8px 10px 8px",
                          width: "100%",
                          boxShadow: "none",
                          backgroundColor: a.PRODUCT_QNT ? "#FFEB3B" : "white",
                          borderRadius: "10px",
                        }}
                      >
                        <span style={{ fontWeight: "600" }}>
                          {a.PRODUCT_NAME}
                        </span>
                        <Grid container spacing={1}>
                          <Grid item xs={5} sm={5} md={5} lg={5}>
                            <TextField
                              label="Qnt"
                              variant="outlined"
                              style={{
                                width: "100%",
                                height: 28,
                                color: "red",
                              }}
                              margin="dense"
                              id={`PRODUCT_QNT_${key++}`}
                              value={a.PRODUCT_QNT}
                              disabled
                            />
                          </Grid>
                          <Grid item xs={5} sm={5} md={5} lg={5}>
                            <TextField
                              label="Amt"
                              variant="outlined"
                              id={`PRODUCT_AMT_${key++}`}
                              style={{ width: "100%", height: 28 }}
                              margin="dense"
                              value={a.PRODUCT_AMT}
                              disabled
                            />
                          </Grid>
                          <Grid item xs={2} sm={2} md={2} lg={2}>
                            <IconButton
                              style={{ marginTop: "5px", marginRight: "2px" }}
                              onClick={() =>
                                setProductsInfo(
                                  i,
                                  a.PRODUCT_NAME,
                                  a.PRODUCT_QNT,
                                  a.PRODUCT_AMT,
                                  a.PRODUCT_ID
                                )
                              }
                            >
                              <EditIcon fontSize="small" />
                            </IconButton>
                          </Grid>
                        </Grid>
                      </Paper>
                      {items[i].length > b + 1 ? (
                        <Divider
                          style={{
                            backgroundColor: "aqua",
                            marginTop: "6px",
                            marginBottom: "6px",
                          }}
                        />
                      ) : null}
                    </div>
                  ))}
                </Paper>
              </Grid>
            ))
          )}
        </Grid>
      </Paper>
    </div>
  );
};

export default ProductOverView;

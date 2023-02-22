import React, { useEffect, useState } from "react";
import Header from "../components/Header";
import { makeStyles } from "@material-ui/core/styles";
import Accordion from "@material-ui/core/Accordion";
import AccordionDetails from "@material-ui/core/AccordionDetails";
import AccordionSummary from "@material-ui/core/AccordionSummary";
import Typography from "@material-ui/core/Typography";
import ExpandMoreIcon from "@material-ui/icons/ExpandMore";
import { Button, InputLabel, MenuItem, Paper, Select } from "@material-ui/core";
import ReportDataTable from "./ReportDataTable";
import { fetchAPIData, getCookie } from "../components/Common";
import { Link } from "react-router-dom";
import Loader from "../components/Loader";

const useStyles = makeStyles((theme) => ({
  heading: {
    fontSize: theme.typography.pxToRem(15),
    flexBasis: "33.33%",
    flexShrink: 0,
    fontWeight: "bold",
  },
  customAccordionColor: {
    backgroundColor: "azure",
    borderRadius: "10px",
  },
  secondaryHeading: {
    fontSize: theme.typography.pxToRem(15),
    color: theme.palette.text.secondary,
  },
  accordionCss: {
    "&. .MuiAccordionSummary-content": {
      display: "inline-block !important",
    },
  },
}));

export default function ProductReport() {
  const classes = useStyles();
  const [productsInfo, setProductsInfo] = useState([]);
  const [loading, setLoading] = useState(true);
  const [months, setMonths] = useState([]);
  const [years, setYears] = useState([]);
  const [userSelectedMonthID, setUserSelectedMonthID] = useState(
    new Date().getMonth()
  );
  useEffect(() => {
    var monthNumber = "";
    if (sessionStorage.getItem("MONTHID")) {
      monthNumber = parseInt(sessionStorage.getItem("MONTHID")) + 1;
    } else {
      monthNumber = userSelectedMonthID + 1;
    }
    const formData = {
      SERVICE_ID: window.location.href.split("/")[5],
      USER_ID: getCookie("userID"),
      MONTH: monthNumber,
      YEAR: sessionStorage.getItem("YEARID"),
    };
    fetchAPIData(
      "/dailyreportsapi/services/getProductsReport.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        console.log(json);
        setProductsInfo(json.records);
        setLoading(false);
      } else {
        console.log(json);
      }
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
  }, [userSelectedMonthID]);
  const listOfYears = (startYear) => {
    var currentYear = new Date().getFullYear(),
      years = [];
    while (startYear <= currentYear) {
      years.push(startYear++);
    }

    return years;
  };

  return (
    <div>
      <Header header="Product Report" />
      {loading ? <Loader /> : null}
      <Link to={`/user/productoverview/${window.location.href.split("/")[5]}`}>
        <Button
          variant="contained"
          color="primary"
          style={{ margin: "10px", float: "right" }}
        >
          Product Overview
        </Button>
      </Link>
      <Paper
        elevation={2}
        style={{
          margin: "10px",
          boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
          marginTop: "60px",
          //   backgroundColor: "lightgrey",
        }}
      >
        <div style={{ display: "flex", margin: "10px" }}>
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
        {productsInfo.map((items, key) => (
          <Accordion
            defaultExpanded={key === 0 ? true : null}
            style={{
              borderRadius: "20px",
              marginBottom: "20px",
              border: "none",
            }}
            key={key}
          >
            <AccordionSummary
              expandIcon={<ExpandMoreIcon />}
              className={classes.customAccordionColor}
            >
              <Typography
                className={(classes.heading, classes.accordionCss)}
                style={{ fontWeight: "bold" }}
              >
                {items.PRODUCT_NAME} |{" "}
                <span>
                  QNT: {items.PRODUCT_QNT} AMT: {items.PRODUCT_AMT}
                </span>
              </Typography>
            </AccordionSummary>
            <AccordionDetails style={{ backgroundColor: "azure" }}>
              {Array(items.PRODUCT_DETAILS).map((productDetails, proKey) => (
                <ReportDataTable
                  columns={productDetails.COL_ARR}
                  rows={productDetails.ROW_ARR}
                  key={proKey}
                />
              ))}
              <ReportDataTable />
            </AccordionDetails>
          </Accordion>
        ))}
      </Paper>
    </div>
  );
}

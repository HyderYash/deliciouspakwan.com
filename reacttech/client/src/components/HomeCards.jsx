import React from "react";
import Card from "@material-ui/core/Card";
import CardContent from "@material-ui/core/CardContent";
import { buildCurrentDateFormat } from "./Common";
import OpenInNewIcon from "@material-ui/icons/OpenInNew";
import Accordion from "@material-ui/core/Accordion";
import AccordionDetails from "@material-ui/core/AccordionDetails";
import AccordionSummary from "@material-ui/core/AccordionSummary";
import Typography from "@material-ui/core/Typography";
import ExpandMoreIcon from "@material-ui/icons/ExpandMore";
import { Divider, makeStyles, Paper } from "@material-ui/core";
import Table from "@material-ui/core/Table";
import TableBody from "@material-ui/core/TableBody";
import TableCell from "@material-ui/core/TableCell";
import TableContainer from "@material-ui/core/TableContainer";
import TableHead from "@material-ui/core/TableHead";
import TableRow from "@material-ui/core/TableRow";

const useStyles = makeStyles((theme) => ({
  root: {
    width: "100%",
  },
  heading: {
    fontSize: theme.typography.pxToRem(15),
    flexBasis: "33.33%",
    flexShrink: 0,
  },
  secondaryHeading: {
    fontSize: theme.typography.pxToRem(15),
    color: theme.palette.text.secondary,
  },
}));

export default function HomeCards({
  title,
  time,
  url,
  description,
  feLang,
  beLang,
  apiLang,
}) {
  const classes = useStyles();

  return (
    <Card>
      <div>
        <CardContent>
          <div
            style={{
              display: "flex",
              alignItems: "center",
              flexWrap: "wrap",
            }}
          >
            <Typography component="h5" variant="h5">
              {title}
            </Typography>
            <a
              href={url}
              target="_blank"
              rel="noreferrer"
              style={{ textDecoration: "none", color: "white" }}
            >
              <OpenInNewIcon
                fontSize="small"
                style={{ marginLeft: "5px", marginTop: "5px" }}
              />
            </a>
          </div>

          <Typography
            variant="subtitle1"
            color="textSecondary"
            style={{ fontSize: "13px" }}
          >
            Last Updated on {buildCurrentDateFormat(time)}
          </Typography>
          <TableContainer component={Paper}>
            <Table>
              <TableHead style={{ backgroundColor: "green" }}>
                <TableRow>
                  <TableCell>Frontend</TableCell>
                  <TableCell>Backend</TableCell>
                  <TableCell>API</TableCell>
                </TableRow>
              </TableHead>
              <TableBody style={{ backgroundColor: "blue" }}>
                <TableRow>
                  <TableCell align="left">{feLang}</TableCell>
                  <TableCell align="left">{beLang}</TableCell>
                  <TableCell align="left">{apiLang}</TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </TableContainer>
        </CardContent>
        <Divider />

        <Accordion>
          <AccordionSummary
            expandIcon={<ExpandMoreIcon />}
            aria-controls="panel4bh-content"
            id="panel4bh-header"
          >
            <Typography className={classes.heading}>More Info...</Typography>
          </AccordionSummary>
          <AccordionDetails style={{ width: "100%" }}>
            {description}
          </AccordionDetails>
        </Accordion>
      </div>
    </Card>
  );
}

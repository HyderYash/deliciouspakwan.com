import React, { Component } from "react";
import { Alert, Card, Col, Container, FormControl, Row } from "react-bootstrap";
import { TodaysDate, YTStartingDate } from "./Common";
import NavBarMenu from "./NavBarMenu";

class YTMonetization extends Component {
  constructor() {
    super();
    this.date1 = new Date(YTStartingDate);
    this.date2 = new Date(TodaysDate);
    var Difference_In_Time = this.date2.getTime() - this.date1.getTime();
    this.Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
    this.state = {
      NoOfVideos: "",
      WatchHours: "",
    };
    sessionStorage.setItem("TotalWatchHours", 4000);
    this.YTTotalHours = 4000;
    sessionStorage.setItem("FirstVideoUpload", YTStartingDate);
  }
  AddDaytoDate() {
    sessionStorage.setItem(
      "DivideTotalWatchHoursAVGWatchTimePerDay",
      sessionStorage.getItem("TotalWatchHours") /
        sessionStorage.getItem("AVGWatchTimePerDay")
    );
    this.DivideTotalWatchHoursAVGWatchTimePerDay = sessionStorage.getItem(
      "DivideTotalWatchHoursAVGWatchTimePerDay"
    );
    var today = new Date("March 5, 2020 5:30:00");
    var MonetizationDate = new Date();
    MonetizationDate.setDate(
      today.getDate() + Number(this.DivideTotalWatchHoursAVGWatchTimePerDay)
    );
    sessionStorage.setItem("MonetizationDate", MonetizationDate);
  }
  render() {
    return (
      <div>
        <NavBarMenu />

        <Container>
          <Alert
            className="mt-3"
            style={{
              width: "auto",
              backgroundColor: "#FD7272",
              border: "4px solid black",
              borderRadius: "10px",
              fontWeight: "bold",
            }}
          >
            <Alert.Heading className="h5">
              {`First Video Upload ${sessionStorage.getItem(
                "FirstVideoUpload"
              )}`}
            </Alert.Heading>
          </Alert>
        </Container>
        <Container fluid>
          <Row>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Card
                className="mt-3 mb-3"
                style={{
                  width: "auto",
                  border: "4px solid black",
                  borderRadius: "10px",
                }}
              >
                <Card.Header
                  style={{
                    backgroundColor: "yellow",
                    borderBottom: "4px solid black",
                  }}
                >
                  <h3>Current Date</h3>
                </Card.Header>
                <Card.Body
                  style={{
                    backgroundColor: "purple",
                    color: "yellow",
                    fontWeight: "bold",
                  }}
                >
                  <h4>{TodaysDate}</h4>
                </Card.Body>
              </Card>
            </Col>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Card
                className="mt-3 mb-3"
                style={{
                  width: "auto",
                  border: "4px solid black",
                  borderRadius: "10px",
                }}
              >
                <Card.Header
                  style={{
                    backgroundColor: "yellow",
                    borderBottom: "4px solid black",
                  }}
                >
                  <h3>No. of Videos</h3>
                </Card.Header>
                <Card.Body
                  style={{
                    backgroundColor: "purple",
                    color: "yellow",
                    fontWeight: "bold",
                  }}
                >
                  <FormControl
                    type="number"
                    placeholder="Enter No of Videos"
                    onChange={(event) => {
                      this.setState({ NoOfVideos: event.target.value });
                    }}
                  />
                </Card.Body>
              </Card>
            </Col>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Card
                className="mt-3 mb-3"
                style={{
                  width: "auto",
                  border: "4px solid black",
                  borderRadius: "10px",
                }}
              >
                <Card.Header
                  style={{
                    backgroundColor: "yellow",
                    borderBottom: "4px solid black",
                  }}
                >
                  <h3>Watch Hours</h3>
                </Card.Header>
                <Card.Body
                  style={{
                    backgroundColor: "purple",
                    color: "yellow",
                    fontWeight: "bold",
                  }}
                >
                  <FormControl
                    type="number"
                    placeholder="Enter Watch Hours"
                    onChange={(event) => {
                      this.setState({ WatchHours: event.target.value });
                    }}
                  />
                </Card.Body>
              </Card>
            </Col>
          </Row>
          <Row>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Card
                className="mt-3 mb-3"
                style={{
                  width: "auto",
                  border: "4px solid black",
                  borderRadius: "10px",
                }}
              >
                <Card.Header
                  style={{
                    backgroundColor: "yellow",
                    borderBottom: "4px solid black",
                  }}
                >
                  <h3>Video Frequency</h3>
                </Card.Header>
                <Card.Body
                  style={{
                    backgroundColor: "purple",
                    color: "yellow",
                    fontWeight: "bold",
                  }}
                >
                  {this.state.NoOfVideos === "" ? (
                    <h4>0</h4>
                  ) : (
                    <h4>
                      {(
                        this.Difference_In_Days / this.state.NoOfVideos
                      ).toFixed(1)}
                    </h4>
                  )}
                </Card.Body>
              </Card>
            </Col>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Card
                className="mt-3 mb-3"
                style={{
                  width: "auto",
                  border: "4px solid black",
                  borderRadius: "10px",
                }}
              >
                <Card.Header
                  style={{
                    backgroundColor: "yellow",
                    borderBottom: "4px solid black",
                  }}
                >
                  <h3>Target</h3>
                </Card.Header>
                <Card.Body
                  style={{
                    backgroundColor: "purple",
                    color: "yellow",
                    fontWeight: "bold",
                  }}
                >
                  <h4>{`${sessionStorage.getItem(
                    "TotalWatchHours"
                  )} HOURS`}</h4>
                </Card.Body>
              </Card>
            </Col>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Card
                className="mt-3 mb-3"
                style={{
                  width: "auto",
                  border: "4px solid black",
                  borderRadius: "10px",
                }}
              >
                <Card.Header
                  style={{
                    backgroundColor: "yellow",
                    borderBottom: "4px solid black",
                  }}
                >
                  <h3>Remaining</h3>
                </Card.Header>
                <Card.Body
                  style={{
                    backgroundColor: "purple",
                    color: "yellow",
                    fontWeight: "bold",
                  }}
                >
                  <h4>
                    {`${
                      sessionStorage.getItem("TotalWatchHours") -
                      this.state.WatchHours
                    } HOURS`}
                  </h4>
                </Card.Body>
              </Card>
            </Col>
          </Row>
          <Row>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Card
                className="mt-3 mb-3"
                style={{
                  width: "auto",
                  border: "4px solid black",
                  borderRadius: "10px",
                }}
              >
                <Card.Header
                  style={{
                    backgroundColor: "yellow",
                    borderBottom: "4px solid black",
                  }}
                >
                  <h3>Spent days</h3>
                </Card.Header>
                <Card.Body
                  style={{
                    backgroundColor: "purple",
                    color: "yellow",
                    fontWeight: "bold",
                  }}
                >
                  <h4>{this.Difference_In_Days}</h4>
                </Card.Body>
              </Card>
            </Col>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Card
                className="mt-3 mb-3"
                style={{
                  width: "auto",
                  border: "4px solid black",
                  borderRadius: "10px",
                }}
              >
                <Card.Header
                  style={{
                    backgroundColor: "yellow",
                    borderBottom: "4px solid black",
                  }}
                >
                  <h3>Avg. watch time per day</h3>
                </Card.Header>
                <Card.Body
                  style={{
                    backgroundColor: "purple",
                    color: "yellow",
                    fontWeight: "bold",
                  }}
                >
                  <h4>
                    {`${(
                      this.state.WatchHours / this.Difference_In_Days
                    ).toFixed(2)} HOURS`}
                    {sessionStorage.setItem(
                      "AVGWatchTimePerDay",
                      (this.state.WatchHours / this.Difference_In_Days).toFixed(
                        2
                      )
                    )}
                  </h4>
                </Card.Body>
              </Card>
            </Col>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Card
                className="mt-3 mb-3"
                style={{
                  width: "auto",
                  border: "4px solid black",
                  borderRadius: "10px",
                }}
              >
                <Card.Header
                  style={{
                    backgroundColor: "yellow",
                    borderBottom: "4px solid black",
                  }}
                >
                  <h3>1 Video watch time per day</h3>
                </Card.Header>
                <Card.Body
                  style={{
                    backgroundColor: "purple",
                    color: "yellow",
                    fontWeight: "bold",
                  }}
                >
                  {this.state.NoOfVideos === "" ? (
                    <h4>0</h4>
                  ) : (
                    <h4>
                      {`${(
                        (sessionStorage.getItem("AVGWatchTimePerDay") /
                          this.state.NoOfVideos) *
                        60
                      ).toFixed(2)} MINUTES`}
                    </h4>
                  )}
                </Card.Body>
              </Card>
            </Col>
          </Row>
        </Container>
        <Container fluid>
          <Card
            className="mt-3 mb-3"
            style={{
              width: "auto",
              border: "4px solid black",
              borderRadius: "10px",
            }}
          >
            <Card.Header
              style={{
                backgroundColor: "darkblue",
                color: "white",
                borderBottom: "4px solid black",
              }}
            >
              <h3>Expected Monetisation Date</h3>
            </Card.Header>
            <Card.Body
              style={{
                backgroundColor: "red",
                color: "yellow",
                fontWeight: "bold",
              }}
            >
              <h3>
                {this.AddDaytoDate()}{" "}
                {sessionStorage.getItem("MonetizationDate")}
              </h3>
            </Card.Body>
            <Card.Footer style={{ backgroundColor: "black", color: "white" }}>
              <h3>183 DAYS REMAINING</h3>
            </Card.Footer>
          </Card>
        </Container>
      </div>
    );
  }
}
export default YTMonetization;

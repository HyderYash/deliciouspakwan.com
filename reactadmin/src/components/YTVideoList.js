import React, { Component } from "react";
import NavBarMenu from "./NavBarMenu";
import { fetchAPIData, isAuthenticated } from "./Common";
import { Redirect } from "react-router-dom";
import {
  Button,
  Col,
  Container,
  Form,
  Modal,
  OverlayTrigger,
  Row,
  Table,
  Tooltip,
} from "react-bootstrap";
import Spinner from "../assets/utility/Loader";
import { CSVLink } from "react-csv";
import Message from "../assets/utility/Message";

class YTVideoList extends Component {
  constructor() {
    super();
    this.state = {
      videoslist: [],
      loading: null,
      APIReturnMessage: "",
      APIReturnStatus: "",
      show: false,
      videoIdarr: [],
    };
  }

  componentDidMount() {
    this.getVideoData();
  }

  getVideoData() {
    this.setState({ loading: true });
    fetchAPIData("/api/listvideos/list_videos.php", "", "POST")
      .then((json) => {
        this.setState({
          loading: false,
        });
        if (json.status === "Success") {
          this.setState({ videoslist: json.records });
        }
      })
      .catch((err) => {
        console.log(err);
      });
  }

  addVideoIdToArr(videoId) {
    const keywordInputPrefix = "key";
    const cb = document.getElementById(videoId);
    const keywordInput = document.getElementById(keywordInputPrefix + videoId);
    if (cb.checked === true) {
      keywordInput.removeAttribute("disabled");
      this.state.videoIdarr.push(videoId);
    } else {
      const index = this.state.videoIdarr.indexOf(videoId);
      if (index > -1) {
        keywordInput.setAttribute("disabled", "true");
        this.state.videoIdarr.splice(index, 1);
      }
    }
  }

  handleModal() {
    this.setState({ show: !this.state.show });
  }
  closeFirstModal() {
    this.setState({ show: false });
  }

  updateVideosById() {
    if (this.state.videoIdarr.length > 0) {
      this.setState({ loading: true });
      var removeDuplicatedFromArr = this.state.videoIdarr.reduce(function (
        a,
        b
      ) {
        if (a.indexOf(b) < 0) a.push(b);
        return a;
      },
      []);
      var finalVideoArr = {
        videoArr: removeDuplicatedFromArr,
      };

      fetchAPIData(
        "/api/updatevideosbyid/update_video_by_id.php",
        finalVideoArr,
        "POST"
      ).then((json) => {
        this.setState({ APIReturnStatus: json.status });
        this.setState({ APIReturnMessage: json.message });
        if (json.status === "Success") {
          this.setState({ videoId: [] });
          this.setState({ loading: false });
        } else {
          this.setState({ loading: false });
        }
      });
    } else {
      alert("Please check any one of the checkboxes to continue");
    }
  }

  updateKeywordsInDB() {
    if (this.state.videoIdarr.length > 0) {
      const keywordInputIDPrefix = "key";
      var keywordInputVals = [];
      this.state.videoIdarr.forEach((item) => {
        var el = document.getElementById(keywordInputIDPrefix + item);
        console.log(el.value);
        keywordInputVals.push({ videoId: item, videoSearchKeywords: el.value });
      });
      this.setState({ loading: true });
      fetchAPIData(
        "/api/video/updateVideoKeywordsById.php",
        keywordInputVals,
        "POST"
      ).then((json) => {
        this.setState({ APIReturnStatus: json.status });
        this.setState({ APIReturnMessage: json.message });
        if (json.status === "Success") {
          this.setState({ videoId: [] });
          this.setState({ loading: false });
          this.getVideoData();
        } else {
          this.setState({ loading: false });
        }
      });
    } else {
      alert("Please check any one of the checkboxes to continue");
    }
  }

  ParentCheckboxFunc() {
    this.setState({ videoIdarr: [] });
    const cb = document.getElementById("selectAllChk");
    var items = document.getElementsByName("chkbox");
    var keywordInputs = document.getElementsByName("keywordInput");
    console.log(keywordInputs);
    for (var i = 0; i < items.length; i++) {
      if (cb.checked === true) {
        keywordInputs[i].removeAttribute("disabled");
        items[i].checked = true;
        this.state.videoIdarr.push(items[i].value);
      } else {
        keywordInputs[i].setAttribute("disabled", "true");
        items[i].checked = false;
        const index = this.state.videoIdarr.indexOf(items[i].value);
        if (index > -1) {
          this.state.videoIdarr.splice(index, 1);
        }
      }
    }
  }

  selectOffSelectAllChkbox() {
    let selectAllBtn = true;
    var items = document.getElementsByName("chkbox");
    for (var i = 0; i < items.length; i++) {
      if (items[i].type === "checkbox" && items[i].checked === false) {
        selectAllBtn = false;
      }
    }
    document.getElementById("selectAllChk").checked = selectAllBtn;
  }

  render() {
    return isAuthenticated ? (
      <div>
        <Modal
          backdrop="static"
          show={this.state.show}
          onHide={() => {
            this.handleModal();
          }}
        >
          <Modal.Header closeButton>
            <b>UPDATE VIDEO</b>
          </Modal.Header>
          <Modal.Body>
            <b>
              If You want to update your VIDEO click the button below. You can
              see the Results In the List Page
            </b>
          </Modal.Body>
          <Modal.Footer>
            <Button
              onClick={() => {
                this.handleModal();
              }}
              variant="danger"
            >
              Cancel
            </Button>
            <Button
              variant="success"
              onClick={() => {
                this.closeFirstModal();
                this.updateVideosById();
              }}
            >
              OK
            </Button>
          </Modal.Footer>
        </Modal>
        <NavBarMenu />
        {this.state.APIReturnStatus === "Success" ? (
          <Message color="success" message={this.state.APIReturnMessage} />
        ) : null}
        {this.state.APIReturnStatus === "Failed" ? (
          <Message color="danger" message={this.state.APIReturnMessage} />
        ) : null}
        <Container fluid className="mt-3">
          <Row>
            <Col xs={12} sm={12} md={4} lg={4}>
              <CSVLink
                data={this.state.videoslist}
                className="btn btn-success ml-auto mb-3 btn-block"
                filename={"VIDEO_LIST.csv"}
                style={{
                  fontSize: "20px",
                  fontWeight: "400",
                  letterSpacing: "2px",
                }}
              >
                Export Table
              </CSVLink>
            </Col>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Button
                className="btn btn-secondary ml-auto mb-3 btn-block"
                style={{
                  fontSize: "20px",
                  fontWeight: "400",
                  letterSpacing: "2px",
                }}
                onClick={() => this.updateKeywordsInDB()}
              >
                Add Keywords
              </Button>
            </Col>
            <Col xs={12} sm={12} md={4} lg={4}>
              <Button
                className="btn btn-primary ml-auto mb-3 btn-block"
                style={{
                  fontSize: "20px",
                  fontWeight: "400",
                  letterSpacing: "2px",
                }}
                onClick={() => {
                  this.handleModal();
                }}
              >
                Update Videos
              </Button>
            </Col>
          </Row>
        </Container>
        {this.state.loading ? (
          <Spinner />
        ) : (
          <>
            <Table
              striped
              bordered
              responsive
              hover
              className="mt-1 mx-auto"
              variant="light"
              size="sm"
              style={{
                width: "100%",
                tableLayout: "auto",
              }}
            >
              <thead>
                <tr
                  style={{
                    background: "#ddd",
                    fontWeight: "800",
                    textTransform: "uppercase",
                  }}
                >
                  <th>ID</th>
                  <th>VIDEO TITLE</th>
                  <th>Keywords</th>
                  <th>
                    Check
                    <OverlayTrigger
                      placement="top"
                      overlay={
                        <Tooltip>
                          <strong>Select All</strong>
                        </Tooltip>
                      }
                    >
                      <input
                        type="checkbox"
                        className="ml-2"
                        id="selectAllChk"
                        onClick={() => this.ParentCheckboxFunc()}
                      />
                    </OverlayTrigger>
                  </th>
                </tr>
              </thead>
              <tbody>
                {this.state.videoslist.map((items, key) => (
                  <tr key={key}>
                    <td style={{ display: "none" }}></td>
                    <td style={{ background: "#A9F5BC" }}>{items.ID}</td>

                    <OverlayTrigger
                      overlay={
                        <Tooltip>
                          <img src={items.VIDEO_THUMB_URL} alt="thumbnail" />
                        </Tooltip>
                      }
                    >
                      <td align="left" style={{ background: "#F3E2A9" }}>
                        {items.VIDEO_TITLE.slice(0, 60) + "..."}
                      </td>
                    </OverlayTrigger>
                    <td style={{ background: "#A9F5BC" }}>
                      <Form.Control
                        type="text"
                        id={"key" + items.VIDEO_ID}
                        defaultValue={items.VIDEO_SEARCH_KEYWORDS}
                        disabled={true}
                        name="keywordInput"
                        key={items.VIDEO_SEARCH_KEYWORDS}
                      />
                    </td>
                    <td align="center" style={{ background: "#A9F5F2" }}>
                      <input
                        type="checkbox"
                        name="chkbox"
                        id={items.VIDEO_ID}
                        onClick={() => this.selectOffSelectAllChkbox()}
                        onChange={() => {
                          this.addVideoIdToArr(items.VIDEO_ID);
                        }}
                        value={items.VIDEO_ID}
                      />
                    </td>
                  </tr>
                ))}
              </tbody>
            </Table>
          </>
        )}
      </div>
    ) : (
      <Redirect to={{ pathname: "/login" }} />
    );
  }
}
export default YTVideoList;

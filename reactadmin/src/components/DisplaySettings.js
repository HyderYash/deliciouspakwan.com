import React, { Component } from "react";
import { Table } from "react-bootstrap";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEdit, faDownload } from "@fortawesome/free-solid-svg-icons";
import { Link } from "react-router-dom";
import NavBarMenu from "./NavBarMenu";
import { fetchAPIData, isAuthenticated } from "./Common";
import { Redirect } from "react-router-dom";
import LastUpdated from "../assets/utility/LastUpdated";
import Spinner from "../assets/utility/Loader";
import { CSVLink } from "react-csv";

class DisplaySettings extends Component {
  constructor() {
    super();
    this.state = {
      list: [],
      PageVistTime: JSON.parse(sessionStorage.getItem("menuRouteLinkDetails"))
        .records[1].LIST_ITEM_LAST_UPDATED,
      loading: true,
    };
  }
  componentDidMount() {
    this.getData();
  }
  getData() {
    fetchAPIData("/api/settings/get_settings.php", "", "POST")
      .then((json) => {
        this.setState({
          loading: false,
        });
        if (json.status === "Success") {
          this.setState({ list: json.records });
          sessionStorage.setItem("API_DATA", JSON.stringify(json.records));
        }
      })
      .catch((err) => {
        console.log(err);
      });
  }
  setCurrentItemId(itemId) {
    // alert(itemId);
    sessionStorage.setItem("CURRENT_SETTINGS_ITEM_ID", itemId);
  }
  render() {
    return isAuthenticated ? (
      <div>
        <NavBarMenu />
        <LastUpdated time={this.state.PageVistTime} />
        <div>
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
                    <th>Display Name</th>
                    <th>Mobile</th>
                    <th>Desktop</th>
                    <th>Operation</th>
                  </tr>
                </thead>
                <tbody>
                  {this.state.list.map((item, key) => (
                    <tr
                      style={{
                        height: "4vh",
                        padding: "0rem",
                      }}
                      key={key}
                    >
                      <td style={{ display: "none" }}></td>
                      <td style={{ background: "#A9F5BC" }}>{item.ID}</td>
                      <td align="left" style={{ background: "#F3E2A9" }}>
                        {item.DISPLAY_NAME}
                      </td>
                      <td style={{ background: "#A9F5F2", fontWeight: "800" }}>
                        {item.MOBILE}
                      </td>
                      <td style={{ background: "#BBFF99", fontWeight: "800" }}>
                        {item.DESKTOP}
                      </td>
                      <td style={{ background: "#F5CF84" }}>
                        <Link
                          to={`/displaysettingsupdate/${item.ID}`}
                          onClick={(event) => {
                            this.setCurrentItemId(item.ID);
                          }}
                        >
                          <FontAwesomeIcon
                            style={{ color: "#526069", fontSize: "20px" }}
                            icon={faEdit}
                          />
                        </Link>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </Table>
              <div className="container">
                <CSVLink
                  data={this.state.list}
                  className="btn btn-success ml-auto mb-3"
                  filename={"API_LIST.csv"}
                  style={{
                    fontSize: "20px",
                    fontWeight: "400",
                    fontFamily: "Bebas Neue",
                    letterSpacing: "2px",
                  }}
                >
                  <FontAwesomeIcon
                    style={{ fontSize: "35px" }}
                    icon={faDownload}
                  />
                </CSVLink>
              </div>
            </>
          )}
        </div>
        {/* {console.clear()} */}
      </div>
    ) : (
      <Redirect to={{ pathname: "/" }} />
    );
  }
}
export default DisplaySettings;

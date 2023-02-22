import React, { Component } from "react";
import * as FaIcons from "react-icons/fa";
import * as AiIcons from "react-icons/ai";
import { Link } from "react-router-dom";
import "../assets/css/Navbar.css";
import { IconContext } from "react-icons";
// import { API_ROOT_PATH } from "./Common";

class NavBarMenu extends Component {
  constructor() {
    super();
    this.state = {
      sidebar: false,
      menuItems: [],
      pageVisitLink: 1,
    };
    this.showSidebar = this.showSidebar.bind(this);
  }

  showSidebar() {
    this.setState({ sidebar: !this.state.sidebar });
  }
  currentClikedPage(currURL) {
    sessionStorage.setItem("CURRENT_CLICKED_PAGE_ID", currURL);
    this.setState({ pageVisitLink: currURL });
  }
  componentDidMount() {
    const initialNavMenu = JSON.parse(
      sessionStorage.getItem("menuRouteLinkDetails")
    ).records;
    var NavMenu = [];
    initialNavMenu.map((item) => {
      if (item.LIST_ITEM_CLICKED_TYPE === "Nav") {
        NavMenu.push(item);
      }
      return NavMenu;
    });
    this.setState({ menuItems: NavMenu });
  }

  render() {
    return (
      <IconContext.Provider value={{ color: "#fff" }}>
        <div className="navbar">
          <Link to="#" className="menu-bars">
            <FaIcons.FaBars onClick={this.showSidebar} />
          </Link>
        </div>
        <nav className={this.state.sidebar ? "nav-menu active" : "nav-menu"}>
          <ul
            className="nav-menu-items"
            onClick={this.showSidebar}
            style={{ padding: "0rem" }}
          >
            <li className="navbar-toggle">
              <Link to="#" className="menu-bars">
                <AiIcons.AiOutlineClose />
              </Link>
            </li>
            <li
              className="nav-text"
              style={{
                paddingLeft: "15px",
                color: "white",
                fontSize: "22px",
                marginTop: "-15px",
              }}
            >
              <span>ADMIN PANEL</span>
            </li>
            {this.state.menuItems.map((item, index) => {
              return (
                <li key={index} className="nav-text">
                  <Link
                    to={item.LIST_ITEM_LINK}
                    onClick={() => {
                      this.currentClikedPage(item.ID);
                    }}
                  >
                    <span>{item.LIST_ITEM_NAME}</span>
                  </Link>
                </li>
              );
            })}
            {/* {sessionStorage.getItem("GoogleUserName") ? (
              <li
                className="nav-text"
                style={{
                  paddingLeft: "8px",
                  color: "white",
                  fontSize: "16px",
                  marginTop: "16rem",
                }}
              >
                <div className="user">
                  <img
                    src={sessionStorage.getItem("GoogleUserImage")}
                    alt="profile thumbnail"
                    style={{
                      width: "50px",
                      height: "50px",
                      pointerEvents: "none",
                      borderRadius: "100%",
                    }}
                  ></img>
                </div>
                <Link to="/adminprofile">
                  <span style={{ marginBottom: "30px", fontSize: "13px" }}>
                    {sessionStorage.getItem("GoogleUserName")}
                  </span>
                </Link>
              </li>
            ) : (
              <>
                <li
                  className="nav-text"
                  style={{
                    paddingLeft: "8px",
                    color: "white",
                    fontSize: "16px",
                    marginTop: "16rem",
                  }}
                >
                  <div className="userProfile">
                    <Link to="/adminprofile">
                      <div className="user">
                        <img
                          src={`${API_ROOT_PATH}/images/img_avatar.png`}
                          alt="profile thumbnail"
                          style={{
                            width: "50px",
                            height: "50px",
                            pointerEvents: "none",
                            borderRadius: "100%",
                          }}
                        ></img>
                      </div>
                      <span
                        style={{
                          marginBottom: "30px",
                          paddingLeft: "8px",
                          textTransform: "capitalize",
                        }}
                      >
                        {sessionStorage.getItem("AdminUserName")}
                      </span>
                    </Link>
                  </div>
                </li>
              </>
            )} */}
          </ul>
        </nav>
      </IconContext.Provider>
    );
  }
}
export default NavBarMenu;

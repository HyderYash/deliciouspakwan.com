import { AppBar, Grid, Paper, TextField, Toolbar } from "@material-ui/core";
import React, { Component } from "react";
import Header from "../components/common/Header";
import HomeCards from "../components/HomeCards";
import { fetchAPIData } from "../components/Common";
import SearchIcon from "@material-ui/icons/Search";
import Loader from "../components/Loader";

class Home extends Component {
  constructor() {
    super();
    this.state = {
      search: "",
      siteInfo: [],
      loading: true,
    };
  }
  componentDidMount() {
    this.getData();
  }
  getData() {
    const formData = {
      FN_NAME: "getSiteInfo",
    };
    fetchAPIData("/", formData, "POST").then((json) => {
      this.setState({ siteInfo: json.records });
      this.setState({ loading: false });
    });
  }
  renderSites = (sites, key) => {
    return (
      <Grid item xs={12} sm={12} md={3} lg={3} key={key}>
        <Paper
          elevation={2}
          style={{
            margin: "10px",
            boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
          }}
        >
          <HomeCards
            title={sites.SITE_NAME}
            time={sites.SITE_LAST_UPDATED}
            url={sites.SITE_URL}
            description={sites.SITE_DESC}
            feLang={sites.FE_LANG}
            beLang={sites.BE_LANG}
            apiLang={sites.API_LANG}
          />
        </Paper>
      </Grid>
    );
  };

  onchange = (e) => {
    this.setState({ search: e.target.value });
  };

  render() {
    const filteredsites = this.state.siteInfo.filter((sites) => {
      return (
        sites.SITE_NAME.toLowerCase().indexOf(
          this.state.search.toLowerCase()
        ) !== -1 ||
        sites.SITE_DESC.toLowerCase().indexOf(
          this.state.search.toLowerCase()
        ) !== -1 ||
        sites.FE_LANG.replaceAll(",", " ")
          .toLowerCase()
          .indexOf(this.state.search.toLowerCase()) !== -1 ||
        sites.BE_LANG.replaceAll(",", " ")
          .toLowerCase()
          .indexOf(this.state.search.toLowerCase()) !== -1 ||
        sites.API_LANG.replaceAll(",", " ")
          .toLowerCase()
          .indexOf(this.state.search.toLowerCase()) !== -1
      );
    });

    return (
      <div>
        {this.state.loading ? <Loader /> : null}
        <Header header="Site Manager" />

        <AppBar
          position="static"
          style={{ marginTop: "0px", backgroundColor: "#303030" }}
        >
          <Toolbar>
            <div
              style={{
                width: "100%",
                display: "flex",
              }}
            ></div>
            <TextField
              label="Search"
              variant="outlined"
              InputProps={{
                endAdornment: <SearchIcon />,
              }}
              style={{
                marginTop: "10px",
                marginBottom: "10px",
                float: "right",
              }}
              onChange={this.onchange}
            />
          </Toolbar>
        </AppBar>
        <Grid container spacing={1} style={{ width: "100%" }}>
          {filteredsites.length > 0 ? (
            filteredsites.map((sites, key) => {
              return this.renderSites(sites, key);
            })
          ) : (
            <Paper
              elevation={2}
              style={{
                margin: "10px",
                padding: "10px",
                boxShadow: "3px 3px 3px 3px rgba(0, 0, 0, 0.3)",
                marginTop: "20px",
              }}
            >
              <h3>No websites found with this search query</h3>
            </Paper>
          )}
        </Grid>
      </div>
    );
  }
}

export default Home;

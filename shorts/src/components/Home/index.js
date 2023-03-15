import axios, { all } from "axios";
import React, { useEffect, useState } from "react";
import { useRouter } from "next/router";
import moment from "moment";
import Image from "next/image";
import Spinner from "../utils/Spinner";

const Home = () => {
  const [allShorts, setAllShorts] = useState([]);
  // const [filterShorts, setFilterShorts] = useState(true);
  const [loading, setLoading] = useState(true);
  const [recPerPage, setRecPerPage] = useState(7);
  const [currentPageNum, setCurrentPageNum] = useState(1);
  const router = useRouter();

  const getAllShorts = async () => {
    // const ytShortsURL = `https://www.googleapis.com/youtube/v3/search?videoDuration=short&channelId=UCg22-16kmYWZTUQF9wVkqFA&order=date&part=snippet&type=video&maxResults=1000&key=AIzaSyBvoa_cVYNcrQn8SqwaaVb3bCI2rGqxrpQ`;
    const ytShortsURL = `/api/getshortslist`;
    const { data } = await axios.get(ytShortsURL, {
      method: "get",
    });
    setAllShorts(data.records);
    setLoading(false);
  };

  // const toggleFilterShorts = () => {
  //   if (filterShorts === true) {
  //     let finalShortsArr = [];
  //     for (let d of allShorts) {
  //       let isShorts = d.snippet.title.split(" ").includes("#shorts");
  //       if (isShorts === true) {
  //         finalShortsArr.push(d);
  //       }
  //     }
  //     setMappingArr(finalShortsArr);
  //   } else {
  //     setMappingArr(allShorts);
  //   }
  // };

  // const filteredShorts = allShorts.filter((item, key) => {
  //   return (
  //     item.snippet.title
  //       .toLowerCase()
  //       .indexOf(filterShorts === true ? "#shorts".toLowerCase() : "") !== -1
  //   );
  // });

  useEffect(() => {
    if (sessionStorage.getItem("currPageNum") !== null) {
      setCurrentPageNum(parseInt(sessionStorage.getItem("currPageNum")));
    }
    getAllShorts();
  }, []);

  // const createDropDownListArr = () => {
  //   let myArr = [];
  //   for (var i = 8; i <= 48; i += 8) {
  //     myArr.push({ val: i });
  //   }
  //   return myArr;
  // };

  const handleChange = (e) => {
    setRecPerPage(e.target.value);
  };

  const totalNumPages = Math.ceil(allShorts.length / recPerPage);
  const displayPageNumbers = [...Array(totalNumPages + 1).keys()].slice(1);
  const endPageIndex = currentPageNum * recPerPage;
  const startPageIndex = endPageIndex - recPerPage;

  //const visibleShortsList = allShorts.slice(startPageIndex, endPageIndex);
  const visibleShortsList = allShorts.filter((item, key) => {
    return key >= startPageIndex && key <= endPageIndex;
  });

  const sendUserToViewShorts = (videoId) => {
    const shortsData = [];
    for (let shorts of allShorts) {
      if (shorts.VIDEO_ID !== videoId) {
        shortsData.push({ id: shorts.VIDEO_ID });
      }
    }
    router.push({
      pathname: `/shortsviewer/${videoId}`,
    });
    sessionStorage.setItem("SHORTS_ID", JSON.stringify(shortsData));
  };

  if (!loading) {
    return (
      <div className="w-full p-4 bg-white">
        <div className="flex items-end justify-between mb-2 header">
          <div className="flex justify-between title w-full">
            <div>
              {/* <select
                onChange={handleChange}
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              >
                {createDropDownListArr().map((item) => (
                  <option key={item.val - 1} value={item.val - 1}>
                    {item.val}
                  </option>
                ))}
              </select> */}
            </div>
            {/* <p className="text-4xl font-bold text-gray-800">
            Lastest {filterShorts === true ? "Shorts" : "Videos"}
          </p> */}
            {/* <button
            onClick={() => {
              setFilterShorts(!filterShorts);
              toggleFilterShorts();
            }}
          >
            TOGGLE
          </button> */}
            {/* <div className="mb-3">
            <div className="relative inline-block w-10 mr-2 align-middle select-none">
              <input
                type="checkbox"
                name="toggle"
                id="Green"
                className="checked:bg-green-500 outline-none focus:outline-none right-4 checked:right-0 duration-200 ease-in absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                onChange={() => {
                  setFilterShorts(!filterShorts);
                }}
              />
              <label
                htmlFor="Green"
                className="block h-6 overflow-hidden bg-gray-300 rounded-full cursor-pointer"
              ></label>
            </div>
            <span className="font-medium text-gray-400">
              {filterShorts === true ? "Shorts" : "Videos"}
            </span>
          </div> */}
          </div>
        </div>
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
          {visibleShortsList.map((item, key) => (
            <div
              className="overflow-hidden rounded-lg shadow-lg cursor-pointer h-90"
              key={item.VIDEO_ID}
              onClick={() => sendUserToViewShorts(item.VIDEO_ID)}
            >
              {/* <a href="#" className="block w-full h-full"> */}
              <Image
                alt="blog photo"
                src={item.VIDEO_THUMB_URL}
                width="0"
                height="0"
                sizes="100vw"
                className="object-cover w-full h-auto"
              />
              <div className="w-full p-4 bg-white dark:bg-gray-800">
                <p className="mb-2 text-xl font-medium text-gray-800 dark:text-white">
                  {item.VIDEO_TITLE.slice(0, 20)}...
                </p>
                <div className="flex items-center mt-4">
                  <Image
                    alt="profil"
                    src="/assets/images/dp_large.png"
                    className="object-cover rounded-full h-10 w-10"
                    width="0"
                    height="0"
                    sizes="100vw"
                  />
                  <div className="flex flex-col justify-between ml-2 text-sm">
                    <p className="text-gray-800 dark:text-white">
                      Delicious Pakwan
                    </p>
                    <p className="text-gray-400 dark:text-gray-300">
                      {moment(item.VIDEO_PUBLISH_DATE).format("MMMM Do YYYY")}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>

        <nav className="mt-5">
          <ul className="inline-flex -space-x-px">
            <li>
              <span
                onClick={() => {
                  if (currentPageNum > 1) {
                    sessionStorage.setItem("currPageNum", currentPageNum - 1);
                    setCurrentPageNum(currentPageNum - 1);
                  }
                }}
                className="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white cursor-pointer"
              >
                Previous
              </span>
            </li>
            {displayPageNumbers.map((x) => {
              return (
                <li key={x}>
                  <span
                    onClick={() => {
                      sessionStorage.setItem("currPageNum", x);
                      setCurrentPageNum(x);
                    }}
                    className={`px-3 py-2 leading-tight border hover:text-white bg-gray-800 border-gray-700 text-gray-400 hover:bg-gray-700 cursor-pointer ${
                      currentPageNum === x && "bg-gray-700"
                    }`}
                  >
                    {x}
                  </span>
                </li>
              );
            })}

            <li>
              <span
                onClick={() => {
                  if (currentPageNum < totalNumPages) {
                    sessionStorage.setItem("currPageNum", currentPageNum + 1);
                    setCurrentPageNum(currentPageNum + 1);
                  }
                }}
                className="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white  cursor-pointer"
              >
                Next
              </span>
            </li>
          </ul>
        </nav>
      </div>
    );
  } else {
    return <Spinner />;
  }
};

export default Home;

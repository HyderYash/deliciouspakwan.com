import React from "react";
import ReactPlayer from "react-player/youtube";
import { useSwipeable } from "react-swipeable";
import { isMobile } from "react-device-detect";

const DesktopShortsViewer = (props) => {
  const handlers = useSwipeable({
    onSwipedUp: () => props.returnPrevShort(),
    onSwipedDown: () => props.returnNextShort(),
  });
  return (
    <div
      className="flex justify-between h-full w-full p-3 bg-black"
      {...handlers}
    >
      {!isMobile ? (
        <button
          className="flex items-center p-4  transition ease-in duration-200 uppercase rounded-full bg-gray-800 text-white border-2 border-gray-900 focus:outline-none"
          onClick={() => props.returnPrevShort()}
        >
          ⏮️
        </button>
      ) : null}

      <div className="relative p-4 overflow-hidden text-gray-700 bg-white shadow-lg rounded-xl dark:bg-gray-800 dark:text-gray-100 border border-red-500">
        {isMobile ? <div id="video-container"></div> : null}
        <ReactPlayer
          url={`https://www.youtube.com/embed/${props.currentShort.id}?autoplay=1`}
          playing={true}
          onEnded={() => props.handleEnded()}
          controls={true}
          {...handlers}
        />
      </div>
      {!isMobile ? (
        <button
          className="flex items-center p-4  transition ease-in duration-200 uppercase rounded-full bg-gray-800 text-white border-2 border-gray-900 focus:outline-none"
          onClick={() => props.returnNextShort()}
        >
          ⏭️
        </button>
      ) : null}
    </div>
  );
};

export default DesktopShortsViewer;




var autoplaySlider;


/*
$(document).ready(function() {

  $(".wpuf_submit_961").removeAttr("disabled");

  contentDiv = document.getElementById('programa_anchor');
  var fired = 0;


window.addEventListener('scroll', onScrollHandler);

  $(".nav-item").on( "click", function() {

    $('.navbar-collapse').collapse("hide");  
  } );
  
    autoplaySlider = $('#programa').lightSlider({
        auto:false,
        pager:false,
        loop:false,
        item: 3,
        autoWidth: false,
        pauseOnHover:true,
        controls:true,
        enableTouch:false,
        enableDrag:false,
        onSliderLoad: function() {
            $('#programa').removeClass('cS-hidden');
        },
        responsive: [
            {
                breakpoint:1020,
                settings: {
                    item:2,
                    pause:1000,
                    speed:2000,
                    enableTouch:true,
                    enableDrag:true,            
                    loop:true          
                  }
            },
            {
                breakpoint:700,
                settings: {
                    item:1,
                    pause:1000,
                    speed:2000,
                    enableTouch:true,
                    enableDrag:true,            
                    loop:true
                  }
            }
        ]
    });


    function onScrollHandler(event) {
      //console.log(`Pixels from top: ${contentDiv.getBoundingClientRect().top}`);
      if(contentDiv.getBoundingClientRect().top  < 0 && fired == 0 && screen.width < 1020){
        autoplaySlider.goToNextSlide();
        autoplaySlider.goToNextSlide();
        autoplaySlider.goToNextSlide();
        fired = 1;
      }
    }
});


*/

$(window).on('load', function(){
//loopFunction(gsap.utils.toArray(".box"));
// loopFunction(gsap.utils.toArray(".box2"));
// loopFunction(gsap.utils.toArray(".box3"),true);
//loopFunction(gsap.utils.toArray(".box4"),false,true);
// loopFunction(gsap.utils.toArray(".box5"));
// loopFunction(gsap.utils.toArray(".box6"));

function loopFunction(wrapperBoxes,isReversed = false,isPaused = false) {
  var size = wrapperBoxes.length * wrapperBoxes[0].clientWidth; // number of elements times their width
  var x = window.matchMedia("(max-width: "+size+"px)");
  if (x.matches) { // If media query matches
    var loop = horizontalLoop(wrapperBoxes, {
      repeat: -1,
      speed: 0.3,
      reversed: isReversed,
      spaceBefore:0,
      paused:isPaused,
      draggable: true, // make it draggable
      center: true // active element is the one in the center of the container rather than th left edge
    });0
  }
  x.addEventListener("change", () => {
    loopFunction(wrapperBoxes);
}); 
}
/*
This helper function makes a group of elements animate along the x-axis in a seamless, responsive loop.

Features:
 - Uses xPercent so that even if the widths change (like if the window gets resized), it should still work in most cases.
 - When each item animates to the left or right enough, it will loop back to the other side
 - Optionally pass in a config object with values like draggable: true, center: true, speed (default: 1, which travels at roughly 100 pixels per second), paused (boolean), repeat, reversed, and paddingRight.
 - The returned timeline will have the following methods added to it:
   - next() - animates to the next element using a timeline.tweenTo() which it returns. You can pass in a vars object to control duration, easing, etc.
   - previous() - animates to the previous element using a timeline.tweenTo() which it returns. You can pass in a vars object to control duration, easing, etc.
   - toIndex() - pass in a zero-based index value of the element that it should animate to, and optionally pass in a vars object to control duration, easing, etc. Always goes in the shortest direction
   - current() - returns the current index (if an animation is in-progress, it reflects the final index)
   - times - an Array of the times on the timeline where each element hits the "starting" spot.
 */
function horizontalLoop(items, config) {
  items = gsap.utils.toArray(items);
  config = config || {speed:50};
  let onChange = config.onChange,
    lastIndex = 0,
    tl = gsap.timeline({
      repeat: config.repeat,
      onUpdate:
        onChange &&
        function () {
          let i = tl.closestIndex();
          if (lastIndex !== i) {
            lastIndex = i;
            onChange(items[i], i);
          }
        },
      paused: config.paused,
      defaults: { ease: "none" },
      onReverseComplete: () => tl.totalTime(tl.rawTime() + tl.duration() * 100)
    }),
    length = items.length,
    startX = items[0].offsetLeft,
    times = [],
    widths = [],
    spaceBefore = [],
    xPercents = [],
    curIndex = 0,
    indexIsDirty = false,
    center = config.center,
    pixelsPerSecond = (config.speed || 1) * 100,
    snap = config.snap === false ? (v) => v : gsap.utils.snap(config.snap || 1), // some browsers shift by a pixel to accommodate flex layouts, so for example if width is 20% the first element's width might be 242px, and the next 243px, alternating back and forth. So we snap to 5 percentage points to make things look more natural
    timeOffset = 0,
    container =
      center === true
        ? items[0].parentNode
        : gsap.utils.toArray(center)[0] || items[0].parentNode,
    totalWidth,
    getTotalWidth = () =>
      items[length - 1].offsetLeft +
      (xPercents[length - 1] / 100) * widths[length - 1] -
      startX +
      spaceBefore[0] +
      items[length - 1].offsetWidth *
        gsap.getProperty(items[length - 1], "scaleX") +
      (parseFloat(config.paddingRight) || 0),
    populateWidths = () => {
      let b1 = container.getBoundingClientRect(),
        b2;
      items.forEach((el, i) => {
        widths[i] = parseFloat(gsap.getProperty(el, "width", "px"));
        xPercents[i] = snap(
          (parseFloat(gsap.getProperty(el, "x", "px")) / widths[i]) * 100 +
            gsap.getProperty(el, "xPercent")
        );
        b2 = el.getBoundingClientRect();
        spaceBefore[i] = b2.left - (i ? b1.right : b1.left);
        b1 = b2;
      });
      gsap.set(items, {
        // convert "x" to "xPercent" to make things responsive, and populate the widths/xPercents Arrays to make lookups faster.
        xPercent: (i) => xPercents[i]
      });
      totalWidth = getTotalWidth();
    },
    timeWrap,
    populateOffsets = () => {
      timeOffset = center
        ? (tl.duration() * (container.offsetWidth / 2)) / totalWidth
        : 0;
      center &&
        times.forEach((t, i) => {
          times[i] = timeWrap(
            tl.labels["label" + i] +
              (tl.duration() * widths[i]) / 2 / totalWidth -
              timeOffset
          );
        });
    },
    getClosest = (values, value, wrap) => {
      let i = values.length,
        closest = 1e10,
        index = 0,
        d;
      while (i--) {
        d = Math.abs(values[i] - value);
        if (d > wrap / 2) {
          d = wrap - d;
        }
        if (d < closest) {
          closest = d;
          index = i;
        }
      }
      return index;
    },
    populateTimeline = () => {
      let i, item, curX, distanceToStart, distanceToLoop;
      tl.clear();
      for (i = 0; i < length; i++) {
        item = items[i];
        curX = (xPercents[i] / 100) * widths[i];
        distanceToStart = item.offsetLeft + curX - startX + spaceBefore[0];
        distanceToLoop =
          distanceToStart + widths[i] * gsap.getProperty(item, "scaleX");
        tl.to(
          item,
          {
            xPercent: snap(((curX - distanceToLoop) / widths[i]) * 100),
            duration: distanceToLoop / pixelsPerSecond
          },
          0
        )
          .fromTo(
            item,
            {
              xPercent: snap(
                ((curX - distanceToLoop + totalWidth) / widths[i]) * 100
              )
            },
            {
              xPercent: xPercents[i],
              duration:
                (curX - distanceToLoop + totalWidth - curX) / pixelsPerSecond,
              immediateRender: false
            },
            distanceToLoop / pixelsPerSecond
          )
          .add("label" + i, distanceToStart / pixelsPerSecond);
        times[i] = distanceToStart / pixelsPerSecond;
      }
      timeWrap = gsap.utils.wrap(0, tl.duration());
    },
    refresh = (deep) => {
      let progress = tl.progress();
      tl.progress(0, true);
      populateWidths();
      deep && populateTimeline();
      populateOffsets();
      deep && tl.draggable
        ? tl.time(times[curIndex], true)
        : tl.progress(progress, true);
    },
    proxy;
  gsap.set(items, { x: 0 });
  populateWidths();
  populateTimeline();
  populateOffsets();
  window.addEventListener("resize", () => refresh(true));
  function toIndex(index, vars) {
    vars = vars || {};
    Math.abs(index - curIndex) > length / 2 &&
      (index += index > curIndex ? -length : length); // always go in the shortest direction
    let newIndex = gsap.utils.wrap(0, length, index),
      time = times[newIndex];
    if (time > tl.time() !== index > curIndex && index !== curIndex) {
      // if we're wrapping the timeline's playhead, make the proper adjustments
      time += tl.duration() * (index > curIndex ? 1 : -1);
    }
    if (time < 0 || time > tl.duration()) {
      vars.modifiers = { time: timeWrap };
    }
    curIndex = newIndex;
    vars.overwrite = true;
    gsap.killTweensOf(proxy);
    return vars.duration === 0
      ? tl.time(timeWrap(time))
      : tl.tweenTo(time, vars);
  }
  tl.toIndex = (index, vars) => toIndex(index, vars);
  tl.closestIndex = (setCurrent) => {
    let index = getClosest(times, tl.time(), tl.duration());
    if (setCurrent) {
      curIndex = index;
      indexIsDirty = false;
    }
    return index;
  };
  tl.current = () => (indexIsDirty ? tl.closestIndex(true) : curIndex);
  tl.next = (vars) => toIndex(tl.current() + 1, vars);
  tl.previous = (vars) => toIndex(tl.current() - 1, vars);
  tl.times = times;
  tl.progress(1, true).progress(0, true); // pre-render for performance
  if (config.reversed) {
    tl.vars.onReverseComplete();
    tl.reverse();
  }
  if (config.draggable && typeof Draggable === "function") {
    proxy = document.createElement("div");
    let wrap = gsap.utils.wrap(0, 1),
      ratio,
      startProgress,
      draggable,
      dragSnap,
      lastSnap,
      initChangeX,
      align = () =>
        tl.progress(
          wrap(startProgress + (draggable.startX - draggable.x) * ratio)
        ),
      syncIndex = () => {
        // -- CUSTOM THROW COMPLETE LOGIC
        if (draggable.startX - draggable.x > 0) {
          tl.reversed(false);
          gsap.to(tl, {
            ease: "power1.in",
            duration: 1,
            timeScale: 1
          });
        } else {
          tl.reversed(true);
          gsap.to(tl, {
            ease: "power1.in",
            duration: 1,
            timeScale: -1
          });
        }
        // -- END CUSTOM LOGIC
        tl.closestIndex(true);
      };
    typeof InertiaPlugin === "undefined" &&
      console.warn(
        "InertiaPlugin required for momentum-based scrolling and snapping. https://greensock.com/club"
      );
    draggable = Draggable.create(proxy, {
      trigger: items[0].parentNode,
      type: "x",
      onPressInit() {
        let x = this.x;
        gsap.killTweensOf(tl);
        startProgress = tl.progress();
        refresh();
        ratio = 1 / totalWidth;
        initChangeX = startProgress / -ratio - x;
        gsap.set(proxy, { x: startProgress / -ratio });
      },
      onDrag: align,
      onThrowUpdate: align,
      overshootTolerance: 0,
      inertia: true,
      snap(value) {
        //note: if the user presses and releases in the middle of a throw, due to the sudden correction of proxy.x in the onPressInit(), the velocity could be very large, throwing off the snap. So sense that condition and adjust for it. We also need to set overshootTolerance to 0 to prevent the inertia from causing it to shoot past and come back
        if (Math.abs(startProgress / -ratio - this.x) < 10) {
          return lastSnap + initChangeX;
        }
        let time = -(value * ratio) * tl.duration(),
          wrappedTime = timeWrap(time),
          snapTime = times[getClosest(times, wrappedTime, tl.duration())],
          dif = snapTime - wrappedTime;
        Math.abs(dif) > tl.duration() / 2 &&
          (dif += dif < 0 ? tl.duration() : -tl.duration());
        lastSnap = (time + dif) / tl.duration() / -ratio;
        return lastSnap;
      },
      onRelease() {
        syncIndex();
        draggable.isThrowing && (indexIsDirty = true);
      },
      onThrowComplete: syncIndex
    })[0];
    tl.draggable = draggable;
  }
  tl.closestIndex(true);
  lastIndex = curIndex;
  onChange && onChange(items[curIndex], curIndex);
  return tl;
}



//sliders extra effects

   // JavaScript for seamless looping

        // Function to enable seamless vertical looping
        function enableVerticalLooping(section) {
          const images = section.querySelectorAll('.image-container');
          const totalImages = images.length;

          // Adjust scroll position to the first original image
          section.scrollTop = images[0].offsetHeight;

          section.addEventListener('scroll', () => {
              const scrollTop = section.scrollTop;
              const firstImageHeight = images[0].offsetHeight;
              const lastImageHeight = images[totalImages - 1].offsetHeight;
              const totalScrollHeight = section.scrollHeight;

              // When scrolling up from the first image clone
              if (scrollTop <= 0) {
                  section.scrollTop = totalScrollHeight - (2 * firstImageHeight);
              }

              // When scrolling down from the last image clone
              if (scrollTop >= totalScrollHeight - section.clientHeight) {
                  section.scrollTop = firstImageHeight;
              }

              // Show contact message when scrolling vertically
              if (scrollTop > images[0].offsetHeight / 2) {
                  section.classList.add('scrolled');
              } else {
                  section.classList.remove('scrolled');
              }
          });
      }

      // Apply vertical looping to each section
      document.querySelectorAll('.vertical-section').forEach(section => {
          enableVerticalLooping(section);
      });

      // Function to enable seamless horizontal looping
      function enableHorizontalLooping(container) {
          const sections = container.querySelectorAll('.vertical-section');
          const totalSections = sections.length;

          // Adjust scroll position to the first original section
          container.scrollLeft = sections[1].offsetWidth;

          container.addEventListener('scroll', () => {
              const scrollLeft = container.scrollLeft;
              const firstSectionWidth = sections[1].offsetWidth;
              const lastSectionWidth = sections[totalSections - 2].offsetWidth;
              const totalScrollWidth = container.scrollWidth;

              // When scrolling left from the first section clone
              if (scrollLeft <= 0) {
                  container.scrollLeft = totalScrollWidth - (2 * firstSectionWidth);
              }

              // When scrolling right from the last section clone
              if (scrollLeft >= totalScrollWidth - container.clientWidth) {
                  container.scrollLeft = firstSectionWidth;
              }
          });
      }

      // Apply horizontal looping to the container
      const horizontalContainer = document.getElementById('horizontal-container');
      enableHorizontalLooping(horizontalContainer);





});



  


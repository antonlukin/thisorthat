import SweetScroll from 'sweet-scroll';

export default function scroller(distance, callback) {
  const scroll = new SweetScroll({
    duration: 600,
    complete: callback,
  });

  scroll.to(distance);
}
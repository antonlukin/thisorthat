import ParticlesBg from 'particles-bg'

import './styles.scss';

const Background = function() {
  const config= {
    num: [4, 7],
    rps: 1,
    radius: [3, 6],
    life: [8, 10],
    v: [0.5, 1],
    tha: [-50, 50],
    alpha: [0.75, 0],
    scale: [0.5, 1],
    position: "all",
    color: '#ffffff',
    random: 2,
    g: 0.25,
  };

  return (
    <ParticlesBg type="custom" config={config} />
    // <div className="particles-bg-canvas-self"></div>
  );
}

export default Background;
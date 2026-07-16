const ALERT_SOUND_SRC = "/sounds/hopdong.mp3";

let audio: HTMLAudioElement | null = null;
let unlocked = false;

function getAudio(): HTMLAudioElement {
  if (!audio) {
    audio = new Audio(ALERT_SOUND_SRC);
    audio.preload = "auto";
  }
  return audio;
}

function unlockAudioPlayback(): void {
  if (unlocked || typeof window === "undefined") {
    return;
  }

  const clip = getAudio();
  clip.muted = true;
  void clip
    .play()
    .then(() => {
      clip.pause();
      clip.currentTime = 0;
      clip.muted = false;
      unlocked = true;
    })
    .catch(() => {
      clip.muted = false;
    });
}

if (typeof window !== "undefined") {
  window.addEventListener("pointerdown", unlockAudioPlayback, { once: true });
}

export function playContractOrderAlertSound(): void {
  if (typeof window === "undefined") {
    return;
  }

  const clip = getAudio();
  clip.currentTime = 0;
  void clip.play().catch(() => {
    // Browser may block autoplay until the admin interacts with the page.
  });
}

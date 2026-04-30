import { getConfigByAtt } from './attributesToConfigObj'

document.addEventListener('DOMContentLoaded', () => {
	const attributeId = 'videotrigger'
	const elements = document.querySelectorAll(`[data-${attributeId}]`)

	if (!elements.length) return

	const VideoTimeTrigger = class {
		constructor(targetDOMElement, config = {}) {
			const { time = 0, callback = null, once = false } = config

			this.el = targetDOMElement
			this.video = targetDOMElement.tagName === 'VIDEO' ? targetDOMElement : targetDOMElement.querySelector('video')
			this.time = Number(time)
			this.callback = callback
			this.once = once === 'true' || once === '1' ? true : false
			this.fired = false

			if (!this.video) {
				console.warn('[videotrigger] No <video> found for', targetDOMElement)
				return
			}

			this.setup()
		}

		setup = () => {
			this.video.addEventListener('timeupdate', () => {
				if (this.fired) {
					if (!this.once && this.video.currentTime < this.time) {
						this.fired = false
					}
					return
				}
				if (this.video.currentTime >= this.time) {
					this.fire()
				}
			})
		}

		fire = () => {
			this.fired = true

			this.el.dispatchEvent(
				new CustomEvent('videotrigger', {
					bubbles: true,
					detail: { time: this.video.currentTime, video: this.video },
				})
			)

			if (this.callback && typeof window[this.callback] === 'function') {
				window[this.callback](this.video, this.el)
			} else if (this.callback) {
				console.warn(`[videotrigger] Callback function "${this.callback}" not found on window.`)
			}
		}

		reset = () => {
			this.fired = false
		}
	}

	elements.forEach(el => {
		const config = getConfigByAtt(el, attributeId)
		el.videoTimeTrigger = new VideoTimeTrigger(el, config)
	})
})

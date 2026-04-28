/**
 * Scroll to a name (anchor) on the page with smooth animation.
 *
 */

import { gsap } from 'gsap'
// import { ScrollTrigger } from 'gsap/ScrollTrigger'
import { ScrollToPlugin } from 'gsap/all'

gsap.registerPlugin(ScrollToPlugin)

document.addEventListener('DOMContentLoaded', () => {
	// window.addEventListener('load', () => {
	const aNames = document.querySelectorAll('a[href*="#"]')
	if (!aNames.length) return
	let activeLenisTween = null

	const getTargetScrollY = ({ target, offset = 0 }) => {
		if (typeof target === 'number') {
			return Math.max(0, target)
		}

		const targetElement = typeof target === 'string' ? document.querySelector(target) : target
		if (!targetElement) return null

		const absoluteTop = targetElement.getBoundingClientRect().top + window.scrollY
		return Math.max(0, absoluteTop - (offset ?? 0))
	}

	const scrollWithEngine = ({ target, offset, onComplete }) => {
		if (window.lenis && typeof window.lenis.scrollTo === 'function') {
			const targetY = getTargetScrollY({ target, offset: offset ?? 0 })
			if (targetY === null) return

			if (activeLenisTween) activeLenisTween.kill()

			const tweenState = {
				y: window.lenis.animatedScroll ?? window.lenis.actualScroll ?? window.scrollY,
			}

			activeLenisTween = gsap.to(tweenState, {
				y: targetY,
				duration: 0.7,
				ease: 'expo.inOut',
				overwrite: 'auto',
				onUpdate: () => {
					window.lenis.scrollTo(tweenState.y, { immediate: true, force: true })
				},
				onComplete: () => {
					activeLenisTween = null
					if (typeof onComplete === 'function') onComplete()
				},
				onInterrupt: () => {
					activeLenisTween = null
				},
			})
			return
		}

		gsap.to(window, {
			duration: 0.7,
			scrollTo: {
				y: target,
				offsetY: offset ?? 0,
			},
			ease: 'expo.inOut',
			onComplete,
		})
	}

	const linkToSamePage = linkElement => {
		const { origin, pathname, hash } = new URL(document.location)
		return (origin === linkElement.origin && pathname === linkElement.pathname) ?? false
	}

	aNames.forEach(a => {
		const hrefValue = a.getAttribute('href')

		if (hrefValue.split('#')[1].length && linkToSamePage(a)) {
			// const aTarget = console.log(hrefValue, hrefValue.split('#')[1])
			a.addEventListener('click', e => {
				e.preventDefault()
				const href = e.currentTarget.getAttribute('href')
				if (!href || !href.includes('#')) return
				const target = href.split('#')[1]

				const filteredTarget = target === 'top' ? false : target

				//automatic offset if WP menu is present
				const offset_WP_menu = document.querySelector('#masthead')?.getBoundingClientRect() ?? { height: 0 }

				if (document.querySelector(`#${target}`) || !filteredTarget) {
					scrollWithEngine({
						target: filteredTarget ? `#${filteredTarget}` : 0,
						// offset: offset_WP_menu.height ?? 0,
						offset: 0,
						onComplete: () => {
							history.pushState(null, null, `#${target}`)
						},
					})
				}
			})
		}
	})

	if (window.location.hash) {
		const target = window.location.hash.split('#')[1]
		scrollWithEngine({
			target: `#${target}`,
			offset: 0,
		})
	}
})

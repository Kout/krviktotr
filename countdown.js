// credits: https://dev.to/thedevdrawer/simple-countdown-timer-using-javascript-4af7
// class to create a countdown timer
class CountdownTimer {
	// setup timer values
	constructor({ selector, targetDate, backgroundColor = null, foregroundColor = null }) {
		this.selector = selector;
		this.targetDate = targetDate;
		this.backgroundColor = backgroundColor;
		this.foregroundColor = foregroundColor;

		// grab divs on frontend using supplied selector ID
		this.refs = {
			days: document.querySelector(`${this.selector} [data-unit="dní"]`),
			hours: document.querySelector(`${this.selector} [data-unit="hodin"]`),
			mins: document.querySelector(`${this.selector} [data-unit="minut"]`),
			secs: document.querySelector(`${this.selector} [data-unit="vteřin"]`),
		};
	}

	getTimeRemaining(endtime) {
		const total = Date.parse(endtime) - Date.parse(new Date());
		const days = Math.floor(total / (1000 * 60 * 60 * 24));
		const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
		const mins = Math.floor((total / 1000 / 60) % 60);
		const secs = Math.floor((total / 1000) % 60);
		return {
			total,
			days,
			hours,
			mins,
			secs,
		};
	}

	updateTimer({ days, hours, mins, secs }) {
		this.refs.days.textContent = days;
		this.refs.hours.textContent = hours;
		this.refs.mins.textContent = mins;
		this.refs.secs.textContent = secs;
	}

	startTimer() {
		const timer = this.getTimeRemaining(this.targetDate);
		this.updateTimer(timer);
		setInterval(() => {
			const timer = this.getTimeRemaining(this.targetDate);
			this.updateTimer(timer);
		}, 1000);
	}
}

const timer = new CountdownTimer({
	selector: "#countdown",
	targetDate: new Date("December, 6 2022 19:00:00"),
});

timer.startTimer();
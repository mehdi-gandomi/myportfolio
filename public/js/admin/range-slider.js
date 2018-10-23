"use strict";

var volumeSlider = document.getElementById('volume');
var scrubberSlider = document.getElementById('scrubber');
var sliders = [volumeSlider, scrubberSlider];

function Slider(slider) {
    this.slider = slider;
    slider.addEventListener('input', function() {
        this.updateSliderOutput();
        this.updateSliderLevel();
    }.bind(this), false);

    this.level = function() {
        var level = this.slider.querySelector('.slider-input');
        return level.value;
    }

    this.levelString = function() {
        return parseInt(this.level());
    }

    this.remaining = function() {
        return 99.5 - this.level();
    }

    this.remainingString = function() {
        return parseInt(this.remaining());
    }

    this.updateSliderOutput = function() {
        var output = this.slider.querySelector('.slider-output');
        var remaining = this.slider.querySelector('.slider-remaining');
        var thumb = this.slider.querySelector('.slider-thumb');
        output.value = this.levelString();
        output.style.right = this.levelString() + '%';
        thumb.style.right = this.levelString()-10 + '%';
        if (remaining) {
            remaining.style.width = this.remainingString() + '%';
        }
    }

    this.updateSlider = function(num) {
        var input = this.slider.querySelector('.slider-input');
        input.value = num;
    }

    this.updateSliderLevel =function() {
        var level = this.slider.querySelector('.slider-level');
        level.style.width = this.levelString()-10 + '%';
    }
}

sliders.forEach(function(slider) {
    new Slider(slider);
});
import { register } from "../js/register.js";
import { login } from "../js/login.js";
import { profilePicture } from "../js/profile-picture.js";
import { likesUnlikes } from '../js/likesUnlikes.js';
import { openCamera } from "./capture/camera.js";
import { initCommentModal, initMoreOptionsModal } from "./modal.js"
import { handleFileUpload } from './upload.js'
import { commentForPublishing } from './comment-publish.js';
import { settings } from "./settings.js";
import { forgotPasswordHandler,resetPasswordHandler } from "./forgot-password.js";
import { initShareButtons } from "./share.js";
import { updateComment } from "./update-comment.js";
import { pagination } from "./nav.js";

document.addEventListener("DOMContentLoaded", () => {

    const signupForm = document.querySelector(".signup-form");
    if (signupForm) register(signupForm);

    const loginForm = document.querySelector(".login-form");
    if (loginForm) login(loginForm);

    const photoForm = document.querySelector(".photo-form");
    if (photoForm) profilePicture(photoForm);

    const commentInputs = document.querySelectorAll(".add-comment-input");
    if (commentInputs) commentForPublishing(commentInputs);

    likesUnlikes();
    forgotPasswordHandler();
    resetPasswordHandler();
    openCamera();
    initShareButtons();
    handleFileUpload();
    settings();
    initCommentModal();
    initMoreOptionsModal();
    updateComment();
    pagination();
});
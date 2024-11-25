<header class="quixotix-header">
    <h1 class="quixotix-billboard-text">
        Quixotix
    </h1>
</header>
<div class="quixotix-card">
    <ul class="quixotix-tabs">
        <li class="quixotix-tab-link quixotix-current" data-quixotix-tab="quixotix-tab-1">Shortcodes</li>
        <li class="quixotix-tab-link" data-quixotix-tab="quixotix-tab-2">Generate</li>
        <li class="quixotix-tab-link" data-quixotix-tab="quixotix-tab-3">About</li>
    </ul>

    <div id="quixotix-tab-1" class="quixotix-tab-content quixotix-current">
        <div id="howto">
            <p class="quixotix-text-node">
                You can inject Quixote Ipsum Text into your template with the follow shortcode.
                <p class="quixotix-text-node">
                    <code class="quixotix-code">
                        [quixotix 11 /]
                    </code>
                    &nbsp;where "11" is the number of characters.
                </p>
                <p class="quixotix-text-node">
                    <code class="quixotix-code">
                        [quixotix 11 words /]
                    </code>
                    &nbsp;injects 11 words.
                </p>
                <p class="quixotix-text-node">
                    <code class="quixotix-code">
                        [quixotix 11 sentences /]
                    </code>
                    &nbsp;injects a paragraph with 11 sentences.
                </p>
            </p>
        </div>
    </div>
    <div id="quixotix-tab-2" class="quixotix-tab-content">
        <div id="generatePosts">
            <p class="quixotix-text-node">
                You can use the form below to inject blog posts onto your site. This is
                for development purposes only and should not be done on production sites.
                (Because that's not what Ipsum Text is for).
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')) ?>">
                    <input type="hidden" class="display-none just-kidding" name="action" value="quixotix_fake_blogpost">
                    <div>
                        I want
                        <input
                                id="quixotix-input-no-of-posts"
                                class="quixotix-number-input"
                                name="number"
                                type="number"
                                type="number"
                                min="1"
                                max="50"
                                required
                        />
                        blog posts right now.
                        <br />
                        I want each post to be between <input
                                class="quixotix-number-input"
                                type="number"
                                name="floor"
                                type="number"
                                min="1"
                                max="50"
                                required
                        />
                        and <input
                                class="quixotix-number-input"
                                type="number"
                                name="ceiling"
                                type="number"
                                min="1"
                                max="50"
                                required
                        /> number of paragraphs.
                        <br />
                    </div>
                <?php

                    wp_nonce_field('quixotix_fake_blogpost', 'quixotix_fake_blogpost_nonce');

                ?>
                    <button class="quixotix-btn">
                        Boom.
                    </button>
                </form>
            </p>
        </div>
    </div>
    <div id="quixotix-tab-3" class="quixotix-tab-content">
        <p class="quixotix-text-node">
            Click this link if you want to support this project:
        </p>
        <p class="quixotix-text-node">
            <a
                    target="_blank"
                    href="https://www.paypal.com/donate/?business=XL47W3LL7V2A2&no_recurring=0&item_name=Buy+a+WordPress+developer+a+coffee.+This+is+100+percent+optional.&currency_code=USD"
            >
                <button
                        class="quixotix-btn"
                        type="button"
                >
                    <i class='bx bxl-paypal'></i> buy me a donut
                </button>
            </a>
        </p>
        <p class="quixotix-text-node">
            This is 100% <strong>optional.</strong> This project is <strong>free</strong> and <strong>always will be.</strong>
        </p>
        <p class="quixotix-text-node">

            This project provides Ipsum text from the novel, Don Quixote
            by Cervantes.  As yet, it only provides the English translation
            as found on Project Gutenberg.  A Spanish version is forthcoming.
            The goal of this project was to allow the developer to inject
            Ipsum text in English (or soon Spanish) that is coherent, and
            fits the correct amount of desired space.  Let us know how we
            can improve.

            The creator of this plugin would like to note that the value
            for "characters" is multibyte.  1 character !== 1 byte.
            1 character === 1 letter, space or special character.
        </p>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        let tabs = document.querySelectorAll('.quixotix-tab-link');
        tabs.forEach(clickedTab => {
            clickedTab.addEventListener('click', () => {
                tabs.forEach(tab => {
                    tab.classList.remove('quixotix-current');
                });
                clickedTab.classList.add('quixotix-current');

                let tabContent = document.querySelectorAll('.quixotix-tab-content');
                tabContent.forEach(content => {
                    content.classList.remove('quixotix-current');
                });

                let clickedTabDataValue = clickedTab.getAttribute('data-quixotix-tab');
                let tabContentToShow = document.querySelector('#'+clickedTabDataValue);
                tabContentToShow.classList.add('quixotix-current');
            });
        });
    });
    document.addEventListener('DOMContentLoaded', (event) => {
        let tabs = document.querySelectorAll('.quixotix-tab-link');
        const quixotixTogBtn = document.querySelector('#quixotix-tog-howto-button');
        const howTo = document.querySelector('#howto');
        const generatePostsButton = document.querySelector('#quixotix-tog-generate-form');
        const generate = document.querySelector('#generatePosts');



        tabs.forEach(clickedTab => {
            clickedTab.addEventListener('click', () => {
                tabs.forEach(tab => {
                    tab.classList.remove('quixotix-current');
                });
                clickedTab.classList.add('quixotix-current');
                let tabContent = document.querySelectorAll('.quixotix-tab-content');
                tabContent.forEach(content => {
                    content.classList.remove('quixotix-current');
                });
                let clickedTabDataValue = clickedTab.getAttribute('data-quixotix-tab');
                let tabContentToShow = document.querySelector('#'+clickedTabDataValue);
                tabContentToShow.classList.add('quixotix-current');
            });
        });

        generatePostsButton.addEventListener('click', () => {
            generate.style.display = howTo.style.display === 'none' ? 'block' : 'none';
        });
        quixotixTogBtn.addEventListener('click', () => {
            howTo.style.display = howTo.style.display === 'none' ? 'block' : 'none';
        });
    });
</script>
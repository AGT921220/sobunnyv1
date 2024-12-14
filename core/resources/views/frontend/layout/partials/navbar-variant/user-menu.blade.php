<ul class="header-cart">
    @if (Auth::guard('web')->check())
        @if (moduleExists('Chat'))
            @php
                $member_find = \Modules\Chat\app\Models\LiveChat::where(
                    'member_id',
                    Auth::guard('web')->user()->id,
                )->first();
            @endphp
            @if (!empty($member_find))
                {{-- <li class="single chatBar">
                    <a href="{{ route('member.live.chat') }}" class="reload_unseen_message_count">
                        <button class="chat"><i class="fa-regular fa-comment-dots"></i>
                            @php
                                $unseen_message_count = \App\Models\User::select('id')
                                    ->withCount([
                                        'member_unseen_message' => function ($q) {
                                            $q->where('is_seen', 0)->where('from_user', 1);
                                        },
                                    ])
                                    ->where('id', auth('web')->id())
                                    ->first();
                            @endphp
                            @if ($unseen_message_count->member_unseen_message_count > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unseen_message_count->member_unseen_message_count ?? '' }}
                                </span>
                            @endif
                        </button>
                    </a>
                </li> --}}
            @else
                {{-- <li class="single chatBar">
                    <a href="{{ route('user.live.chat') }}" class="reload_unseen_message_count">
                        <button class="chat"><i class="fa-regular fa-comment-dots"></i>
                            @php
                                $unseen_message_count = \App\Models\User::select('id')
                                    ->withCount([
                                        'user_unseen_message' => function ($q) {
                                            $q->where('is_seen', 0)->where('from_user', 2);
                                        },
                                    ])
                                    ->where('id', auth('web')->id())
                                    ->first();
                            @endphp

                            @if ($unseen_message_count->user_unseen_message_count > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unseen_message_count->user_unseen_message_count ?? '' }}
                                </span>
                            @endif
                        </button>
                    </a>
                </li> --}}
            @endif
        @endif

        {{-- <li class="single"><a href="{{ route('user.listing.favorite.all') }}" class="heart"><i
                    class="lar la-heart icon"></i></a></li> --}}
                    {{-- <li class="single">
                        <div class="btn-wrapper">
                            <a href="http://localhost:8088" class="logo">
                                    <img src="http://localhost:8088/assets/uploads/media-uploader/logo1717328157.png" alt="">
                                </a>
                        </div>
                    </li>    --}}
        <li class="single userAccount">
            <x-frontend.user.user-profile-image />
            <div class="userAccount-wrapper">
                <ul class="ac-list">
                    <li class="list">
                        <a class="list-title" href="{{ route('user.dashboard') }}">
                            <svg width="14" height="20" viewBox="0 0 14 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1 19V17C1 15.9391 1.42143 14.9217 2.17157 14.1716C2.92172 13.4214 3.93913 13 5 13H9C10.0609 13 11.0783 13.4214 11.8284 14.1716C12.5786 14.9217 13 15.9391 13 17V19M3 5C3 6.06087 3.42143 7.07828 4.17157 7.82843C4.92172 8.57857 5.93913 9 7 9C8.06087 9 9.07828 8.57857 9.82843 7.82843C10.5786 7.07828 11 6.06087 11 5C11 3.93913 10.5786 2.92172 9.82843 2.17157C9.07828 1.42143 8.06087 1 7 1C5.93913 1 4.92172 1.42143 4.17157 2.17157C3.42143 2.92172 3 3.93913 3 5Z"
                                    stroke="#524EB7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    @if(Auth::user()->role!='client')

                    <li class="list">
                        <a class="list-title" href="{{ route('user.all.listing') }}">
                            <svg width="20" height="18" viewBox="0 0 20 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11 2H19M11 6H16M11 12H19M11 16H16M1 2C1 1.73478 1.10536 1.48043 1.29289 1.29289C1.48043 1.10536 1.73478 1 2 1H6C6.26522 1 6.51957 1.10536 6.70711 1.29289C6.89464 1.48043 7 1.73478 7 2V6C7 6.26522 6.89464 6.51957 6.70711 6.70711C6.51957 6.89464 6.26522 7 6 7H2C1.73478 7 1.48043 6.89464 1.29289 6.70711C1.10536 6.51957 1 6.26522 1 6V2ZM1 12C1 11.7348 1.10536 11.4804 1.29289 11.2929C1.48043 11.1054 1.73478 11 2 11H6C6.26522 11 6.51957 11.1054 6.70711 11.2929C6.89464 11.4804 7 11.7348 7 12V16C7 16.2652 6.89464 16.5196 6.70711 16.7071C6.51957 16.8946 6.26522 17 6 17H2C1.73478 17 1.48043 16.8946 1.29289 16.7071C1.10536 16.5196 1 16.2652 1 16V12Z"
                                    stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ __('All Listing') }}
                        </a>
                    </li>
                    @endif
                    @if (moduleExists('Membership'))
                        @if (membershipModuleExistsAndEnable('Membership'))
                            <li class="list">
                                <a class="list-title" href="{{ route('user.membership.all') }}">
                                    <svg width="20" height="14" viewBox="0 0 20 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 1L14 7L19 3L17 13H3L1 3L6 7L10 1Z" stroke="#64748B"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Membership') }}
                                </a>
                            </li>
                        @endif
                    @endif

                    @if (moduleExists('Wallet'))
                        <li class="list">
                            <a class="list-title" href="{{ route('user.wallet.history') }}">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14 5V2C14 1.73478 13.8946 1.48043 13.7071 1.29289C13.5196 1.10536 13.2652 1 13 1H3C2.46957 1 1.96086 1.21071 1.58579 1.58579C1.21071 1.96086 1 2.46957 1 3M1 3C1 3.53043 1.21071 4.03914 1.58579 4.41421C1.96086 4.78929 2.46957 5 3 5H15C15.2652 5 15.5196 5.10536 15.7071 5.29289C15.8946 5.48043 16 5.73478 16 6V9M1 3V15C1 15.5304 1.21071 16.0391 1.58579 16.4142C1.96086 16.7893 2.46957 17 3 17H15C15.2652 17 15.5196 16.8946 15.7071 16.7071C15.8946 16.5196 16 16.2652 16 16V13M17 9V13H13C12.4696 13 11.9609 12.7893 11.5858 12.4142C11.2107 12.0391 11 11.5304 11 11C11 10.4696 11.2107 9.96086 11.5858 9.58579C11.9609 9.21071 12.4696 9 13 9H17Z"
                                        stroke="#64748B" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                {{ __('Wallet') }}
                            </a>
                        </li>
                    @endif

                    @if (moduleExists('Chat'))
                        @php
                            $member_find = \Modules\Chat\app\Models\LiveChat::where(
                                'member_id',
                                Auth::guard('web')->user()->id,
                            )->first();
                        @endphp
                        <li class="list">
                            @if (!empty($member_find))
                                <a href="{{ route('member.live.chat') }}" class="list-title">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14 5V2C14 1.73478 13.8946 1.48043 13.7071 1.29289C13.5196 1.10536 13.2652 1 13 1H3C2.46957 1 1.96086 1.21071 1.58579 1.58579C1.21071 1.96086 1 2.46957 1 3M1 3C1 3.53043 1.21071 4.03914 1.58579 4.41421C1.96086 4.78929 2.46957 5 3 5H15C15.2652 5 15.5196 5.10536 15.7071 5.29289C15.8946 5.48043 16 5.73478 16 6V9M1 3V15C1 15.5304 1.21071 16.0391 1.58579 16.4142C1.96086 16.7893 2.46957 17 3 17H15C15.2652 17 15.5196 16.8946 15.7071 16.7071C15.8946 16.5196 16 16.2652 16 16V13M17 9V13H13C12.4696 13 11.9609 12.7893 11.5858 12.4142C11.2107 12.0391 11 11.5304 11 11C11 10.4696 11.2107 9.96086 11.5858 9.58579C11.9609 9.21071 12.4696 9 13 9H17Z"
                                            stroke="#64748B" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Chat') }}
                                </a>
                            @else
                                <a href="{{ route('user.live.chat') }}" class="list-title">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14 5V2C14 1.73478 13.8946 1.48043 13.7071 1.29289C13.5196 1.10536 13.2652 1 13 1H3C2.46957 1 1.96086 1.21071 1.58579 1.58579C1.21071 1.96086 1 2.46957 1 3M1 3C1 3.53043 1.21071 4.03914 1.58579 4.41421C1.96086 4.78929 2.46957 5 3 5H15C15.2652 5 15.5196 5.10536 15.7071 5.29289C15.8946 5.48043 16 5.73478 16 6V9M1 3V15C1 15.5304 1.21071 16.0391 1.58579 16.4142C1.96086 16.7893 2.46957 17 3 17H15C15.2652 17 15.5196 16.8946 15.7071 16.7071C15.8946 16.5196 16 16.2652 16 16V13M17 9V13H13C12.4696 13 11.9609 12.7893 11.5858 12.4142C11.2107 12.0391 11 11.5304 11 11C11 10.4696 11.2107 9.96086 11.5858 9.58579C11.9609 9.21071 12.4696 9 13 9H17Z"
                                            stroke="#64748B" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Chat') }}
                                </a>
                            @endif
                        </li>
                    @endif

                    <!--Support Ticket -->
                    <li class="list">
                        <a href="{{ route('user.ticket') }}" class="list-title">
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.3555 8.99999H14.1055C13.8071 8.99999 13.521 9.11852 13.31 9.32949C13.099 9.54047 12.9805 9.82662 12.9805 10.125V12.9375C12.9805 13.2359 13.099 13.522 13.31 13.733C13.521 13.944 13.8071 14.0625 14.1055 14.0625H15.2305C15.5288 14.0625 15.815 13.944 16.026 13.733C16.2369 13.522 16.3555 13.2359 16.3555 12.9375V8.99999ZM16.3555 8.99999C16.3555 8.10881 16.1791 7.22645 15.8363 6.40382C15.4936 5.58118 14.9914 4.83456 14.3586 4.20702C13.7258 3.57949 12.9751 3.08346 12.1496 2.74757C11.3242 2.41168 10.4404 2.24256 9.54922 2.24999C8.65866 2.2435 7.77563 2.4133 6.95099 2.74961C6.12635 3.08592 5.37642 3.58209 4.7444 4.20953C4.11238 4.83698 3.61077 5.58329 3.26848 6.40546C2.92619 7.22763 2.74998 8.10941 2.75 8.99999V12.9375C2.75 13.2359 2.86853 13.522 3.0795 13.733C3.29048 13.944 3.57663 14.0625 3.875 14.0625H5C5.29837 14.0625 5.58452 13.944 5.79549 13.733C6.00647 13.522 6.125 13.2359 6.125 12.9375V10.125C6.125 9.82662 6.00647 9.54047 5.79549 9.32949C5.58452 9.11852 5.29837 8.99999 5 8.99999H2.75"
                                    stroke="#7D7D7D" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M16.3555 12.9375V14.625C16.3555 15.2217 16.1184 15.794 15.6965 16.216C15.2745 16.6379 14.7022 16.875 14.1055 16.875H10.0625"
                                    stroke="#7D7D7D" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            {{ __('Support tickets') }}
                        </a>
                    </li>

                    <li class="list">
                        <a class="list-title" href="{{ route('user.listing.favorite.all') }}">
                            <svg width="18" height="18" viewBox="0 0 21 19" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.5003 10.572L11.0003 18L3.5003 10.572C3.00561 10.0906 2.61594 9.51201 2.35585 8.87263C2.09575 8.23325 1.97086 7.54694 1.98904 6.85693C2.00721 6.16691 2.16806 5.48813 2.46146 4.86333C2.75485 4.23853 3.17444 3.68125 3.69379 3.22657C4.21314 2.7719 4.82101 2.42968 5.47911 2.22147C6.13722 2.01327 6.83131 1.94358 7.51767 2.0168C8.20403 2.09001 8.8678 2.30455 9.46718 2.6469C10.0666 2.98925 10.5885 3.45199 11.0003 4.00599C11.4138 3.45602 11.9364 2.99731 12.5354 2.6586C13.1344 2.31988 13.7968 2.10844 14.4812 2.03751C15.1657 1.96658 15.8574 2.03769 16.5131 2.24639C17.1688 2.45508 17.7743 2.79687 18.2919 3.25036C18.8094 3.70385 19.2277 4.25928 19.5207 4.88189C19.8137 5.50449 19.975 6.18088 19.9946 6.8687C20.0142 7.55653 19.8916 8.24099 19.6344 8.87924C19.3773 9.5175 18.9912 10.0958 18.5003 10.578"
                                    stroke="#64748B" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            {{ __('Favourite') }}
                        </a>
                    </li>

                    @if (moduleExists('Membership'))
                        @if (membershipModuleExistsAndEnable('Membership'))
                            <li class="list">
                                <a href="{{ route('user.enquiries.all') }}" class="list-title">
                                    <svg fill="none" height="24" viewBox="0 0 24 24" width="24"
                                        xmlns="http://www.w3.org/2000/svg" transform="rotate(180)">
                                        <path
                                            d="m12 8v4m0 4h.01m8.99-4c0 4.9706-4.0294 9-9 9-4.97056 0-9-4.0294-9-9 0-4.97056 4.02944-9 9-9 4.9706 0 9 4.02944 9 9z"
                                            stroke="#4A5568" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" />
                                    </svg>
                                    <span> {{ __('Enquiries') }}</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    <li class="list">
                        <a class="list-title" href="{{ route('user.profile') }}">
                            <svg width="14" height="20" viewBox="0 0 14 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1 19V17C1 15.9391 1.42143 14.9217 2.17157 14.1716C2.92172 13.4214 3.93913 13 5 13H9C10.0609 13 11.0783 13.4214 11.8284 14.1716C12.5786 14.9217 13 15.9391 13 17V19M3 5C3 6.06087 3.42143 7.07828 4.17157 7.82843C4.92172 8.57857 5.93913 9 7 9C8.06087 9 9.07828 8.57857 9.82843 7.82843C10.5786 7.07828 11 6.06087 11 5C11 3.93913 10.5786 2.92172 9.82843 2.17157C9.07828 1.42143 8.06087 1 7 1C5.93913 1 4.92172 1.42143 4.17157 2.17157C3.42143 2.92172 3 3.93913 3 5Z"
                                    stroke="#524EB7" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            {{ __('Profile') }}
                        </a>
                    </li>
                    <li class="list">
                        <a class="list-title" href="{{ route('user.account.settings') }}">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.325 2.317C8.751 0.561 11.249 0.561 11.675 2.317C11.7389 2.5808 11.8642 2.82578 12.0407 3.032C12.2172 3.23822 12.4399 3.39985 12.6907 3.50375C12.9414 3.60764 13.2132 3.65085 13.4838 3.62987C13.7544 3.60889 14.0162 3.5243 14.248 3.383C15.791 2.443 17.558 4.209 16.618 5.753C16.4769 5.98466 16.3924 6.24634 16.3715 6.51677C16.3506 6.78721 16.3938 7.05877 16.4975 7.30938C16.6013 7.55999 16.7627 7.78258 16.9687 7.95905C17.1747 8.13553 17.4194 8.26091 17.683 8.325C19.439 8.751 19.439 11.249 17.683 11.675C17.4192 11.7389 17.1742 11.8642 16.968 12.0407C16.7618 12.2172 16.6001 12.4399 16.4963 12.6907C16.3924 12.9414 16.3491 13.2132 16.3701 13.4838C16.3911 13.7544 16.4757 14.0162 16.617 14.248C17.557 15.791 15.791 17.558 14.247 16.618C14.0153 16.4769 13.7537 16.3924 13.4832 16.3715C13.2128 16.3506 12.9412 16.3938 12.6906 16.4975C12.44 16.6013 12.2174 16.7627 12.0409 16.9687C11.8645 17.1747 11.7391 17.4194 11.675 17.683C11.249 19.439 8.751 19.439 8.325 17.683C8.26108 17.4192 8.13578 17.1742 7.95929 16.968C7.7828 16.7618 7.56011 16.6001 7.30935 16.4963C7.05859 16.3924 6.78683 16.3491 6.51621 16.3701C6.24559 16.3911 5.98375 16.4757 5.752 16.617C4.209 17.557 2.442 15.791 3.382 14.247C3.5231 14.0153 3.60755 13.7537 3.62848 13.4832C3.64942 13.2128 3.60624 12.9412 3.50247 12.6906C3.3987 12.44 3.23726 12.2174 3.03127 12.0409C2.82529 11.8645 2.58056 11.7391 2.317 11.675C0.561 11.249 0.561 8.751 2.317 8.325C2.5808 8.26108 2.82578 8.13578 3.032 7.95929C3.23822 7.7828 3.39985 7.56011 3.50375 7.30935C3.60764 7.05859 3.65085 6.78683 3.62987 6.51621C3.60889 6.24559 3.5243 5.98375 3.383 5.752C2.443 4.209 4.209 2.442 5.753 3.382C6.753 3.99 8.049 3.452 8.325 2.317Z"
                                    stroke="#64748B" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M7 10C7 10.7956 7.31607 11.5587 7.87868 12.1213C8.44129 12.6839 9.20435 13 10 13C10.7956 13 11.5587 12.6839 12.1213 12.1213C12.6839 11.5587 13 10.7956 13 10C13 9.20435 12.6839 8.44129 12.1213 7.87868C11.5587 7.31607 10.7956 7 10 7C9.20435 7 8.44129 7.31607 7.87868 7.87868C7.31607 8.44129 7 9.20435 7 10Z"
                                    stroke="#64748B" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            {{ __('Settings') }}
                        </a>
                    </li>
                    <li class="list">
                        <a class="list-title" href="{{ route('user.logout') }}">
                            <i class="las la-sign-out-alt icon"></i>
                            {{ __('Logout') }}
                        </a>
                    </li>
                </ul>
            </div>
            <!-- End User AC -->
        </li>
    @else
    <li class="single">
        <div class="btn-wrapper">
            <a href="http://localhost:8088" class="logo">
                    <img src="http://localhost:8088/assets/uploads/media-uploader/logo1717328157.png" alt="">
                </a>
        </div>
    </li>
        <li class="single userAccount">
            <x-frontend.user.user-profile-image />
        </li>
    @endif
    @if (Auth::check())
    @if(Auth::user()->role!='client')
        <li class="single">
            <div class="btn-wrapper">
                <a href="{{ route('user.add.listing') }}" class="cmn-btn1 popup-modal">
                    <i class="las la-plus-circle"></i><span class="text">{{ __('Post your ad') }}</span>
                </a>
            </div>
        </li>
        @endif
    @else
        <li class="single">
            <div class="btn-wrapper">
                <a href="{{ route('guest.add.listing') }}" class="cmn-btn1 popup-modal">
                    <i class="las la-plus-circle"></i><span class="text">{{ __('Post your ad') }}</span>
                </a>
            </div>
        </li>
    @endif
    <li class="single">
        <div class="btn-wrapper">
            <a class="cmn-btn1 popup-modal triggerSearchBar">
                <i class="las la-search"></i> <span class="text">{{ __('Search') }}</span>
            </a>
        </div>
    </li>
</ul>


<div class="cateLeftContent">
    <div class="cateSidebar1">


        <!--Search any title filter start -->
        @if (!empty($listing_search_by_text_on_off))
            <div class="catagoriesWraper mb-4">
                <div class="catagories w-100">
                    <div class="single-category-service">
                        <div class="single-select">
                            <input type="text" class="search-input form-control" id="search_by_query"
                                placeholder="{{ $search_placeholder }}" name="q"
                                value="{{ $text_search_value }}">
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!--Search any title filter end -->

        <!--Distance google map filter -->
        <div class="catagoriesWraper mb-4" style="display: none !important">
            <div class="catagories w-100">
                <!-- autocomplete address -->

                <!-- Distance range-->
                <div id="distance-slider"></div>
                {{-- <div class="slider-container slider-kilometer">
                                <input type="hidden" name="distance_kilometers_value" id="distance_kilometers_value">
                                <div class="cateTitle mb-2">{{__('Distance')}}</div>
                                <div id="slider" class="slider-range mt-2"></div>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <div id="slider-value" class="slider-range-value"></div>
                                    <span class="km_title_text">{{ __('km') }}</span>
                                </div>
                            </div> --}}

                <!-- cancel and apply button start -->
            </div>
        </div>
        <!--google map Distance filter end -->
        <!-- Location -->
        {{-- @if (empty(get_static_option('google_map_settings_on_off')))
            <div class="locaton catagoriesWraper mb-4 class2">
                @if (!empty($country_on_off))
                    <div class="catagories">
                        <select id="search_by_country" name="country" class="categorySelect">
                            <option value="">{{ $country_text }}</option>
                            @foreach ($countries as $cont)
                                <option @if (!empty(request()->get('country')) && request()->get('country') == $cont->id) selected @endif
                                    value="{{ $cont->id }}">{{ $cont->country }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
        @endif --}}

        @if (empty(get_static_option('google_map_settings_on_off')))
            @if (!empty($state_on_off))
                @php  $fetch_cities = '';  @endphp
                @if ($country_on_off !== 'on')
                    @php
                        $get_listing_state_id = $all_listings->pluck('city_id');
                        $all_sates = \Modules\CountryManage\app\Models\City::whereIn('id', $get_listing_state_id)
                            ->where('status', 1)
                            ->get();
                        foreach ($all_sates as $states) {
                            $fetch_cities .=
                                '<option selected value=' . $states->id . '>' . $states->city . '</option>';
                        }
                    @endphp
                @endif
                <div class="catagories">
                    <select id="search_by_state" name="state">
                        <option value=""> {{ $state_text }}</option>
                        @foreach ($listings_state as $listing_state)
                            {
                            <option @if (!empty(request()->get('state')) && request()->get('state') == $listing_state->id) selected @endif
                                value="{{ $listing_state->id }}">{{ $listing_state->state }}</option>
                        @endforeach
                        {{ $fetch_cities }}
                    </select>
                </div>
            @endif
        @endif

        @if (empty(get_static_option('google_map_settings_on_off')))
            @if (!empty($city_on_off))
                <div class="catagories">
                    <select id="search_by_city" name="city">
                        <option value=""> {{ $city_text }}</option>
                        @foreach ($listings_city as $listing_city)
                            
                            <option @if (!empty(request()->get('city')) && request()->get('city') == $listing_city->id) selected @endif
                                value="{{ $listing_city->id }}">{{ $listing_city->city }}</option>
                        @endforeach
                        {{ $fetch_cities ?? 0 }}
                    </select>
                </div>
            @endif
    </div>
    @endif

    @include('filters.filtersListing')

    @if (!empty($sort_by_on_off))
        <div class="catagoriesWraper px-0">
            <div class="catagories mx-3">
                <select id="search_by_sorting" name="sortby">
                    <option value="">{{ __('Sort By') }}</option>
                    @foreach ($sortby_search as $value => $text)
                        <option @if (!empty(request()->get('sortby')) && request()->get('sortby') == $value) selected @endif value="{{ $value }}">
                            {{ $text }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

</div>
</div>

@if(!empty($current_page_url) && strpos($current_page_url, '/listings') === false)
{{-- <p>
    {{$current_page_url}}
</p>
@endif
@if(!empty($current_page_url) && $current_page_url !== 'listings') --}}
<form method="get" action="/listings" id="search_listings_form">
    <input type="hidden" name="listing_grid_and_list_view" id="listing_grid_and_list_view">
    <input type="hidden" name="date_posted_listing" id="date_posted_listing">
    <input type="hidden" name="listing_condition" id="listing_condition">
    <input type="hidden" name="listing_type_preferences" id="listing_type_preferences">
</form>

@endif

<style>
    .cateLeftContent {
        position: absolute;
        height: 100%;
        top: 95px;
        z-index: -10;
        background: white;
        right: 25px;
        display: none !important;
    }

    .cateSidebar1 {
        background: white;
    }

    .new-style .cateSidebar1 .catagoriesWraper {
        box-shadow: 8px 10px 8px 10px rgba(0, 0, 0, 0.32);
    }

    .cateLeftContent {
        /* transform: translateX(100%); */
        opacity: 0;
        transition: transform 0.5s ease, opacity 0.5s ease;
    }

    .cateLeftContent.active {
        transform: translateX(0);
        opacity: 1;
        z-index: 10000000000000000;
        display: block !important;
    }
</style>
